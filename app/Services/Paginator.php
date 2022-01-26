<?php

namespace app\Services;

class Paginator implements PaginatorInterface
{

    /**
     * {@inheritDoc}
     */
    public static function getPages(int $currentPage, int $countRows, int $limit): array
    {
        $countPages = intval(ceil($countRows / $limit));
        if ($currentPage > $countPages or $currentPage < 1) {
            $currentPage = 1;
        }
        $pages = [];
        if (1 !== $currentPage) {
            $pages['prevFromCurrentPage'] = $currentPage - 1;
        }
        $pages['currentPage'] = $currentPage;
        if ($currentPage < $countPages and $currentPage < $countPages - 1) {
            $pages['nextFromCurrentPage'] = $currentPage + 1;
        }
        if ($currentPage !== $countPages) {
            $pages['lastPage'] = $countPages;
        }
        return $pages;
    }
}