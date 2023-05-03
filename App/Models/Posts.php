<?php
namespace App\Models;

use Core\Model;

/**
 * Posts model.
 */
class Posts extends Model
{
    /**
     * Get all posts.
     *
     * @return array
     */
    public static function getPosts()
    {
        try
        {
            $db = static::getDB();
            $stmt = $db->query('SELECT id, title, content FROM posts ORDER BY created_at DESC');
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return $results;
        }
        catch (\PDOException $e)
        {
            throw new \Exception($e->getMessage(), 500);
        }
    }
}