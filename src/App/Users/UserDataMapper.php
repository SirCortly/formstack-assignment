<?php
namespace App\Users;

use PDO;
use App\{AbstractDomainObject, AbstractDataMapper};
use App\Users\User;

class UserDataMapper extends AbstractDataMapper
{
    /**
     * @var string Basic SELECT FROM query for users
     */
    private $select_from = "
        SELECT
            `id`,
            `email`,
            `password`,
            `firstname`,
            `lastname`,
            `created_at`,
            `updated_at`
        FROM `users`
    ";

    /**
     * Fetch All Users
     *
     * @return User[]
     */
    public function fetchAll() : array
    {
        // Grab all rows from users table
        $results = $this->db->query($this->select_from)
            ->fetchAll(PDO::FETCH_ASSOC);

        // Map results into array of User objects
        return array_map(function($result) {
            return $this->_populate($result);
        }, $results);
    }

    /**
     * Fetch a single User by ID
     *
     * @param int $id Id of User
     *
     * @return AbstractDomainObject
     */
    public function fetchById(int $id) : AbstractDomainObject
    {
        // Append WHERE clause to $this->select_from and execute query
        $stmt = $this->db->prepare(
            $this->select_from . 'WHERE id = ?'
        );
        $stmt->execute([$id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // If ID was not found, throw Exception
        if ( ! $result) {
            throw new \Exception('User not found');
        }

        // Return User object
        return $this->_populate($result);
    }

    /**
     * Delete User
     *
     * @param AbstractDomainObject $user User to delete
     *
     * @return void
     */
    public function delete(AbstractDomainObject $user)
    {
        if (is_null($user->getId())) {
            throw new \Exception('Cannot delete user where ID is null');
        }

        $stmt = $this->db->prepare("
            DELETE FROM `users` WHERE id = ?
        ");
        $stmt->execute([$user->getId()]);

        // ID was not found. Nothing was deleted.
        if ( ! $stmt->rowCount()) {
            throw new \Exception('Cannot delete User who does not exist');
        }
    }

    /**
     * Create new User
     *
     * @param AbstractDomainObject $user User to create
     *
     * @return int New User ID
     */
    protected function _create(AbstractDomainObject $user) : int
    {
        $stmt = $this->db->prepare("
            INSERT INTO `users` (
                `email`, `password`, `firstname`, `lastname`, `created_at`
            )
            VALUES (:email, :password, :firstname, :lastname, NOW());
        ");

        $data = [
            ':email' => $user->getEmail(),
            ':password' => $user->getPassword(),
            ':firstname' => $user->getFirstname(),
            ':lastname' => $user->getLastname()
        ];
        $stmt->execute($data);

        return $this->db->lastInsertId();
    }

    /**
     * Update existing User
     *
     * @param AbstractDomainObject $user User to updated
     *
     * @return int Updated User ID
     */
    protected function _update(AbstractDomainObject $user) : int
    {
        $stmt = $this->db->prepare("
            UPDATE `users`
            SET
                `email` = :email,
                `password` = :password,
                `firstname` = :firstname,
                `lastname` = :lastname,
                `updated_at` = NOW()
            WHERE id = :id;
        ");

        $data = [
            ':id' => $user->getId(),
            ':email' => $user->getEmail(),
            ':password' => $user->getPassword(),
            ':firstname' => $user->getFirstname(),
            ':lastname' => $user->getLastname()
        ];
        $stmt->execute($data);

        return $user->getId();
    }

    /**
     * Turn a data array into a User object
     *
     * @param array $row Array corresponding to MysQL row
     *
     * @return User
     */
    private function _populate(array $row) : User
    {
        $user = new User();

        $user->setId($row['id']);
        $user->setEmail($row['email']);
        $user->setPassword($row['password']);
        $user->setFirstname($row['firstname']);
        $user->setLastname($row['lastname']);
        $user->setCreatedAt(new \DateTime($row['created_at']));

        if ( ! is_null($row['updated_at'])) {
            $user->setUpdatedAt(new \DateTime($row['updated_at']));
        }

        return $user;
    }
}
