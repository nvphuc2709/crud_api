<?php


namespace App\Repositories;


interface PostRepositoryInterface
{
    /**
     * Get's all posts.
     *
     * @return mixed
     * @param array
     */
    public function getAll(array $data);

    /**
     * Get's a post by it's ID
     *
     * @param int
     */
    public function get($id);

    /**
     * Creating a post.
     *
     * @param array
     */
    public function create(array $data = []);

    /**
     * Updating a post.
     *
     * @param int
     * @param array
     */
    public function update($id, array $data = []);

    /**
     * Deleting a post.
     *
     * @param int
     */
    public function delete($id);
}
