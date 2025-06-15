const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

// Ensure public directories exist
const publicDirs = ['css', 'js', 'fonts'];
publicDirs.forEach(dir => {
    const dirPath = path.join('public', dir);
    if (!fs.existsSync(dirPath)) {
        fs.mkdirSync(dirPath, { recursive: true });
    }
});

// Process CSS with Tailwind
console.log('Processing CSS with Tailwind...');
try {
    execSync('npx tailwindcss -i ./resources/css/app.css -o ./public/css/app.css --minify', { stdio: 'inherit' });
    console.log('✓ CSS processed successfully');
} catch (error) {
    console.error('Error processing CSS with Tailwind:', error);
    process.exit(1);
}

// Copy JavaScript
console.log('Copying JavaScript files...');
try {
    // Simple copy for now - in a real project, you might want to bundle with esbuild/rollup
    const jsContent = `// Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Your custom JavaScript here
console.log('Eheca frontend loaded!');
`;
    
    fs.writeFileSync('./public/js/app.js', jsContent);
    console.log('✓ JavaScript files copied');
} catch (error) {
    console.error('Error copying JavaScript files:', error);
    process.exit(1);
}

// Copy any other assets (e.g., images, fonts)
console.log('Copying asset files...');
try {
    // Example: Copy font files if they exist
    if (fs.existsSync('./resources/fonts')) {
        execSync('cp -r ./resources/fonts/* ./public/fonts/');
    }
    
    console.log('✓ Assets copied');
} catch (error) {
    console.error('Error copying assets:', error);
    process.exit(1);
}

console.log('\n✅ Build completed successfully!');
console.log('   Run `php -S localhost:8000 -t public` to start the development server');
