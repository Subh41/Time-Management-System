<?php
header('Content-Type: application/json');

$tasksFile = 'tasks.json';

function getTasks() {
    global $tasksFile;
    if (file_exists($tasksFile)) {
        return json_decode(file_get_contents($tasksFile), true);
    }
    return [];
}

function saveTasks($tasks) {
    global $tasksFile;
    file_put_contents($tasksFile, json_encode($tasks));
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode(getTasks());
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $tasks = getTasks();
    $newTask = [
        'id' => time(),
        'name' => $input['name'],
        'description' => $input['description'],
        'dateTime' => $input['dateTime'],
        'completed' => false
    ];
    $tasks[] = $newTask;
    saveTasks($tasks);
    echo json_encode($newTask);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);
    $tasks = getTasks();
    foreach ($tasks as &$task) {
        if ($task['id'] == $input['id']) {
            $task['completed'] = $input['completed'];
            break;
        }
    }
    saveTasks($tasks);
    echo json_encode(['success' => true]);
}