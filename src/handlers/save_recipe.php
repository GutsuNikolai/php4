<?php
/**
 * Включение отображения ошибок
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once('../helpers.php');

$errors = [];

/**
 * Обработка данных формы
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    $recipeFile = '../../storage/recipes.txt';
    
    /**
     * Фильтрация входных данных
     */
    $title = filterData($_POST['title']);
    $category = filterData($_POST['category']);
    $ingredients = filterData($_POST['ingredients']);
    $description = filterData($_POST['description']);
    $steps = $_POST['steps'];
    $tags = $_POST['tags'];

    /**
     * Валидация данных
     */
    if (empty($title)) {
        $errors['title'] = 'Название рецепта обязательно!';
    }
    if (empty($category)) {
        $errors['category'] = 'Категория рецепта обязательна!';
    }
    if (empty($ingredients)) {
        $errors['ingredients'] = 'Ингредиенты обязательны!';
    }
    if (empty($description)) {
        $errors['description'] = 'Описание рецепта обязательно!';
    }
    if (empty($steps)) {
        $errors['steps'] = 'Шаги приготовления обязательны!';
    }

    /**
     * Если нет ошибок, сохраняем данные
     */
    if (empty($errors)) {
        /**
         * Формируем данные для записи в файл
         */
        $formData = [
            'title' => $title,
            'category' => $category,
            'ingredients' => $ingredients,
            'description' => $description,
            'steps' => $steps,
            'tags' => $tags
        ];

        /**
         * Проверяем, существует ли файл с рецептами и загружаем его содержимое
         */
        $existingData = file_exists($recipeFile) ? json_decode(file_get_contents($recipeFile), true) : [];

        // Добавляем новый рецепт в массив
        $existingData[] = $formData;

        /**
         * Сохраняем данные в файл
         */
        if (file_put_contents($recipeFile, json_encode($formData) . PHP_EOL, FILE_APPEND)) {
            // Перенаправляем на главную страницу
            header("Location: /public/index.php");
            exit;
        } else {
            $errors['general'] = 'Ошибка при сохранении рецепта.';
        }
    }
}
?>
