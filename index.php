<?php

// Only valid if PHP7 or greater
//declare(strict_types=1);

/**
 * AUTHOR : AVONTURE Christophe.
 *
 * Written date : 24 october 2018
 *
 * Simple JSON_Encode ajax interface.
 *
 * Last mod:
 * 2018-12-31 - Abandonment of jQuery and migration to vue.js
 */

define('REPO', 'https://github.com/cavo789/json_encode');

// Retrieve posted data
$data = json_decode(file_get_contents('php://input'), true);
if ($data !== []) {
    $task = filter_var(($data['task'] ?? ''), FILTER_SANITIZE_STRING);

    if ('encode' == $task) {
        // Retrieve the JSON string
        $JSON = base64_decode(filter_var(($data['json'] ?? ''), FILTER_SANITIZE_STRING));
        // Return the encoded JSON
        header('Content-Type: text/html');
        $JSON = json_encode(utf8_encode($JSON));
        echo base64_encode($JSON);
        die();
    }
}

// Get the GitHub corner
$github = '';
if (is_file($cat = __DIR__ . DIRECTORY_SEPARATOR . 'octocat.tmpl')) {
    $github = str_replace('%REPO%', REPO, file_get_contents($cat));
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta name="author" content="Christophe Avonture" />
        <meta name="robots" content="noindex, nofollow" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8;" />
        <title>JSON Encode</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <style>
            pre {
                white-space: pre-wrap;       /* css-3 */
                white-space: -moz-pre-wrap;  /* Mozilla, since 1999 */
                white-space: -pre-wrap;      /* Opera 4-6 */
                white-space: -o-pre-wrap;    /* Opera 7 */
                word-wrap: break-word;       /* Internet Explorer 5.5+ */
            }
        </style>
    </head>
    <body>
        <?php echo $github; ?>
        <div class="container">
            <div class="page-header"><h1>JSON Encode</h1></div>
            <div class="container" id="app">
                <div class="form-group">
                    <how-to-use demo="https://raw.githubusercontent.com/cavo789/json_encode/master/images/demo.gif">
                        <ul>
                            <li>Type (or paste) a text in the text area here below</li>
                            <li>Press the Encode button</li>
                        </ul>
                    </how-to-use>
                    <label for="JSON">Copy/Paste your text in the 
                        textbox below then click on the Encode button:</label>
                    <textarea class="form-control" rows="5" v-model="JSON" name="JSON"></textarea>
                </div>
                <button type="button" @click="processEncode" class="btn btn-primary">Encode</button>
                <hr/>
                <pre v-html="HTML"></pre>
            </div>
        </div>
        <script src="https://unpkg.com/vue"></script>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <script type="text/javascript">
            Vue.component('how-to-use', {
                props: {
                    demo: {
                        type: String,
                        required: true
                    }
                },
                template:
                    `<details>
                        <summary>How to use?</summary>
                        <div class="row">
                            <div class="col-sm">
                                <slot></slot>
                            </div>
                            <div class="col-sm"><img v-bind:src="demo" alt="Demo"></div>                            
                        </div>
                    </details>`
            });

            var app = new Vue({
                el: '#app',
                data: {
                    JSON: "this is a test\nPaição São Paulo\nNuit d'Été, ç'eût\n" +
                        "(Sobre el Bé i el Mal) de Ciceró\ninntrykk av å være lesbar",
                    HTML: ''
                },
                methods: {
                    processEncode() {
                        var $data = {
                            task: 'encode',
                            json: window.btoa(this.JSON)
                        }
                        axios.post('<?php echo basename(__FILE__);?>', $data)
                            .then(response => (this.HTML = window.atob(response.data)))
                            .catch(function (error) {console.log(error);});
                    }
                }
            });
        </script>
    </body>
</html>
