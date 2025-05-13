<?php
namespace Modules\Core_Auth\Repository;

use Doctrine\DBAL\Connection;

class CustomerRepository
{
    private Connection $conn;

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    public function findByEmail(string $email): ?array
    {
        return $this->conn->fetchAssociative('SELECT * FROM customers WHERE email = ?', [$email]);
    }

    public function insert(array $data): int
    {
        $this->conn->insert('customers', $data);
        return (int)$this->conn->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $this->conn->update('customers', $data, ['id' => $id]);
    }

    public function delete(int $id): void
    {
        $this->conn->delete('customers', ['id' => $id]);
    }

    // Add more methods as needed for your use cases
}
