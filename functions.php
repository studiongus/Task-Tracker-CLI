<?php

function initializeStorage(): void
{
    if (!is_dir('data')) {
        mkdir('data', 0777, true);
    }

    if (!file_exists('data/tasks.json')) {
        file_put_contents('data/tasks.json', json_encode([]));
    }
}

function loadTasks(): array
{
    return json_decode(file_get_contents('data/tasks.json'), true) ?? [];
}

function saveTasks(array $tasks): void
{
    file_put_contents('data/tasks.json', json_encode(array_values($tasks), JSON_PRETTY_PRINT));
}

function printCommands(): void
{
    $commands = [
        'add',
        'update',
        'delete',
        'mark-in-progress',
        'mark-done',
        'list',
        'list done',
        'list todo',
        'list in-progress',
    ];

    echo "Please enter a valid command \n";

    foreach ($commands as $command) {
        echo " - {$command} \n";
    }
}

function updateStatus(array $tasks, ?string $id, string $status)
{
    $found = false;

    foreach ($tasks as &$task) {
        if ($task['id'] == $id) {
            $found = true;
            
            if ($status == $task['status']) {
                echo "bruh, it is the same status, just don't update it. \n";

                break;
            }

            $task['status'] = $status;
            $task['updatedAt'] = date("Y-m-d H:i:s");

            saveTasks($tasks);
            
            echo "Status marked to {$status} (ID: {$id}) \n";

            break;
        }
    }
    
    if ($found === false) {
        if ($id === null) {
            echo "Please input the ID mate. \n";
        } else {
            echo "Task not found \n";
        }
    }
}

function listTasksByStatus(array $tasks, ?string $status)
{
    $found = false;
                
    foreach ($tasks as $task) {
        if ($status === null || $task['status'] === $status) {
            $found = true;

            echo " (ID: {$task['id']}) {$task['description']} \n";
        }
    }

    if ($found === false) {
        if ($status === null) {
            echo "There is no task to be listed\n";
        } else {
            echo "There is no task with status {$status} \n";
        }
    }
}
