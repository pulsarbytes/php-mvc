<?php
namespace App\Models;

use Core\Model;

/**
 * Post model.
 */
class Post extends Model
{
    /**
     * Get a post.
     *
     * @param int $id The post id
     *
     * @return array
     */
    public static function getPost($id)
    {
        try
        {
            $db = static::getDB();
            $stmt = $db->prepare('SELECT id, title, content, created_at FROM posts WHERE id = :id');
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            return $result;
        }
        catch (\PDOException $e)
        {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    /**
     * Save post to the database.
     *
     * @param array $data The post data
     *
     * @return int|boolean The row id on success, false otherwise
     */
    public static function save($data)
    {
        if (!isset($data['title']) || !isset($data['content']))
        {
            // Handle missing keys appropriately
            return false;
        }

        try
        {
            $db = static::getDB();
            $stmt = $db->prepare('INSERT INTO posts (title, content, created_at) VALUES (:title, :content, NOW())');
            $stmt->bindValue(':title', $data['title']);
            $stmt->bindValue(':content', $data['content']);
            $stmt->execute();

            // Return the ID of the newly inserted row
            return $db->lastInsertId();
        }
        catch (\PDOException $e)
        {
            throw new \Exception($e->getMessage(), 500);

            return false;
        }
    }
}