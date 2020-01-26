<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Имя</th>
            <th scope="col">Фамилия</th>
            <th scope="col">Пол</th>
            <th scope="col">Почта</th>
            <th scope="col">Номер группы</th>
            <th scope="col">Количество баллов на ЕГЭ</th>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach ($listStudents as $key => $value) {
        ?>
        <tr>
            <th scope="row"><?= htmlspecialchars($value['id']); ?></th>
            <td><?= htmlspecialchars($value['name']) ?></td>
            <td><?= htmlspecialchars($value['surname']) ?></td>
            <td><?php if ($value['sex'] === "0") {echo "М"; } else {echo "Ж";} ?></td>
            <td><?= htmlspecialchars($value['email']) ?></td>
            <td><?= htmlspecialchars($value['groupName']) ?></td>
            <td><?= htmlspecialchars($value['scoreEge']) ?></td>
        </tr>
        <?php
    }
    ?>
    </tbody>
</table>