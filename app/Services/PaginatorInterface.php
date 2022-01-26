<?php

namespace app\Services;

interface PaginatorInterface
{
    /**
     * Should return links in associative array
     * Keys: currentPage, prevFromCurrentPage, nextFromCurrentPage, lastPage
     *
     * @param int $currentPage
     * @param int $countRows Count of all rows in table
     * @param int $limit Limit per page to paginate them
     * @return array array with links
     */
    public static function getPages(int $currentPage, int $countRows, int $limit): array;
}