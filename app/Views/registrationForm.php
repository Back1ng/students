<form method="POST" action="<?php if (isset($student)) {echo "/updateStudent";} else {echo "/addNewStudent";} ?>">
    <div class="form-row">
        <div class="col-md-4">
            <label for="fieldName">Имя</label>
            <input required type="text" class="form-control" id="fieldName" name="fieldName" placeholder="Имя" value="<?php if(isset($student)) echo $student->getName(); ?>">
        </div>
        <div class="col-md-4">
            <label for="fieldSurname">Фамилия</label>
            <input required type="text" class="form-control" id="fieldSurname" name="fieldSurname" placeholder="Фамилия" value="<?php if(isset($student)) echo $student->getSurname(); ?>">
        </div>
        <div class="col-md-2">
            <label for="fieldSex">Пол</label>
            <select required class="form-control" id="fieldSex" name="fieldSex">
                <option <?php if (isset($student)) {if ($student->getSex() === "0") {echo "selected";}} ?> value="0">М</option>
                <option <?php if (isset($student)) {if ($student->getSex() === "1") {echo "selected";}} ?> value="1">Ж</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="fieldCitizenship">Гражданство</label>
            <select required class="form-control" id="fieldCitizenship" name="fieldCitizenship">
                <option <?php if (isset($student)) {if ($student->getCitizenship() === "0") {echo "selected";}} ?> value="0">Местный</option>
                <option <?php if (isset($student)) {if ($student->getCitizenship() === "1") {echo "selected";}} ?> value="1">Иногородний</option>
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-2">
            <label for="fieldGroup">Группа</label>
            <input required type="text" class="form-control" id="fieldGroup" name="fieldGroup" placeholder="Группа"  value="<?php if(isset($student)) echo $student->getGroup(); ?>">
        </div>
        <div class="col-md-2">
            <label for="fieldScoreEge">Баллы ЕГЭ</label>
            <input required type="number" class="form-control" id="fieldScoreEge" name="fieldScoreEge" placeholder="160" value="<?php if(isset($student)) echo $student->getScoreEge(); ?>">
        </div>
        <div class="col-md-6">
            <label for="fieldEmail">Электронная почта</label>
            <input required type="email" class="form-control" id="fieldEmail" name="fieldEmail" placeholder="email@email.email" value="<?php if(isset($student)) echo $student->getEmail(); ?>">
        </div>
        <div class="col-md-2">
            <label for="fieldDateBirth">Дата рождения</label>
            <input required type="date" class="form-control" id="fieldDateBirth" name="fieldDateBirth" value="<?php if(isset($student)) echo $student->getDateBirth(); ?>">
        </div>
    </div>
    <input type="submit" class="btn btn-primary mt-2 float-right">
</form>