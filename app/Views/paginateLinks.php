<nav class="pagination">
    <?php if (isset($paginateLinks['prevFromCurrentPage'])) { ?>
        <li class="page-item"><a class="page-link" href="<?= '/?page=' . $paginateLinks['prevFromCurrentPage'] ?>">Предыдущая страница</a></li>
    <?php } ?>

    <?php if (1 !== $paginateLinks['currentPage'] and $paginateLinks['prevFromCurrentPage'] > 1) { ?>
    <li class="page-item"><a class="page-link" href="/?page=1">1</a></li>
    <?php } ?>

    <?php if (isset($paginateLinks['prevFromCurrentPage']) and $paginateLinks['prevFromCurrentPage'] > 2) { ?>
        <li class="page-item"><a class="page-link">...</a></li>
    <?php } ?>

    <?php if (isset($paginateLinks['prevFromCurrentPage'])) { ?>
        <li class="page-item"><a class="page-link" href="<?= '/?page=' .$paginateLinks['prevFromCurrentPage'] ?>"><?= $paginateLinks['prevFromCurrentPage'] ?></a></li>
    <?php } ?>

    <li class="page-item"><a class="page-link" href="<?= '/?page=' . $paginateLinks['currentPage']; ?>"><?= $paginateLinks['currentPage'] ?></a></li>

    <?php if (isset($paginateLinks['nextFromCurrentPage'])) { ?>
        <li class="page-item"><a class="page-link" href="<?= '/?page=' . $paginateLinks['nextFromCurrentPage']; ?>"><?= $paginateLinks['nextFromCurrentPage'] ?></a></li>
    <?php } ?>

    <?php if (isset($paginateLinks['lastPage'])) { ?>
        <li class="page-item"><a class="page-link">...</a></li>
        <li class="page-item"><a class="page-link" href="<?= '/?page=' . $paginateLinks['lastPage']; ?>"><?= $paginateLinks['lastPage'] ?></a></li>
    <?php } ?>

    <?php if (isset($paginateLinks['nextFromCurrentPage'])) { ?>
        <li class="page-item"><a class="page-link" href="<?= '/?page=' .$paginateLinks['nextFromCurrentPage'] ?>">Следующая страница</a></li>
    <?php } ?>
    </ul>
</nav>