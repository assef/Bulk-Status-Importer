<?php

/**
 * Plugin Name: Bulk Status Importer
 * Description: Bulk change post status from CSV file (supports all post types).
 * Version: 1.0.0
 * Author: Leonardo Assef
 * Author URI: https://github.com/assef
 * License: MIT
 * License URI: https://opensource.org/licenses/MIT
 */

if (!defined('ABSPATH')) exit;

// Add admin menu
add_action('admin_menu', function () {
    add_menu_page('Bulk Status Importer', 'Bulk Status Importer', 'manage_options', 'bulk-status-importer', 'bsi_admin_page');
});

function bsi_admin_page()
{
?>
    <div class="wrap">
        <h1>Bulk Status Importer</h1>

        <h2>Como preparar o CSV</h2>
        <p>O arquivo CSV deve conter as seguintes colunas obrigatórias:</p>
        <ul>
            <li><strong>url</strong>: A URL completa do post, página ou custom post type.</li>
            <li><strong>status</strong>: O status que deseja atribuir ao post.</li>
        </ul>

        <p><strong>Status aceitos:</strong> publish, draft, pending, future, private, trash</p>
        <p><strong>Status especiais:</strong> Se informar <em>delete</em>, <em>deleted</em> ou <em>trash</em>, o post será movido para a lixeira.</p>

        <h3>Exemplo de tabela CSV:</h3>
        <pre style="background: #f1f1f1; padding: 10px;">
url,status
https://seusite.com.br/post-1,draft
https://seusite.com.br/post-2,delete
https://seusite.com.br/post-3,publish
        </pre>

        <hr />

        <h2>Importar arquivo CSV</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="bsi_csv" accept=".csv" required />
            <?php submit_button('Importar CSV'); ?>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['bsi_csv'])) {
            echo '<h2>Resultado da Importação</h2>';
            bsi_process_csv($_FILES['bsi_csv']['tmp_name']);
        }
        ?>
    </div>
<?php
}

function bsi_process_csv($csv_file)
{
    if (($handle = fopen($csv_file, 'r')) === false) {
        echo '<p>Erro ao abrir o arquivo.</p>';
        return;
    }

    $header = fgetcsv($handle, 0, ',');
    $columns = array_map('strtolower', $header);

    $col_url = array_search('url', $columns);
    $col_status = array_search('status', $columns);

    if ($col_url === false || $col_status === false) {
        echo "<p>O CSV deve conter as colunas 'url' e 'status'.</p>";
        return;
    }

    echo '<table class="widefat"><thead>
        <tr><th>URL</th><th>Status</th><th>Resultado</th></tr></thead><tbody>';

    while (($row = fgetcsv($handle, 0, ',')) !== false) {
        $url = trim($row[$col_url]);
        $status = trim($row[$col_status]);

        $result = bsi_process_row($url, $status);
        echo '<tr>';
        echo "<td>{$url}</td><td>{$status}</td><td>{$result}</td>";
        echo '</tr>';
    }
    echo '</tbody></table>';

    fclose($handle);
}

function bsi_process_row($url, $status)
{
    $post_id = url_to_postid($url);
    if (!$post_id) {
        $path = wp_parse_url($url, PHP_URL_PATH);
        $post = get_page_by_path(ltrim($path, '/'), OBJECT, get_post_types(['public' => true, '_builtin' => false]));
        if ($post) $post_id = $post->ID;
    }
    if (!$post_id) return 'Post não encontrado';

    $valid_statuses = ['publish', 'pending', 'draft', 'future', 'private', 'trash', 'auto-draft', 'inherit'];
    $to_delete = in_array(strtolower($status), ['delete', 'deleted', 'trash']);

    if ($to_delete) {
        wp_trash_post($post_id);
        return 'Post movido para a lixeira';
    } elseif (in_array(strtolower($status), $valid_statuses)) {
        wp_update_post(['ID' => $post_id, 'post_status' => strtolower($status)]);
        return "Status alterado para {$status}";
    } else {
        return "Status inválido: {$status}";
    }
}
