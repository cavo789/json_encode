<?php

// Only valid if PHP7 or greater
//declare(strict_types=1);

/**
 * AUTHOR : AVONTURE Christophe.
 *
 * Written date : 24 october 2018
 *
 * Simple JSON_Encode ajax interface.
 */

define('REPO', 'https://github.com/cavo789/json_encode');

$task = filter_input(INPUT_POST, 'task', FILTER_SANITIZE_STRING);

if ('encode' == $task) {
    // Retrieve the JSON string
    $JSON = base64_decode(filter_input(INPUT_POST, 'json', FILTER_SANITIZE_STRING));

    // Return the encoded JSON
    header('Content-Type: text/html');
    $JSON = json_encode(utf8_encode($JSON));
    echo base64_encode($JSON);

    die();
}

// Sample string; with accentuated characters and
$JSON = "this is a test\n" .
    "Paição São Paulo\n" .
    "Nuit d'Été, ç'eût\n" .
    "(Sobre el Bé i el Mal) de Ciceró\n" .
    'inntrykk av å være lesbar';

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
            <div class="container">
                <div class="form-group">
                    <details>
                        <summary>How to use?</summary>
                        <div class="row">
                                <div class="col-sm">
                                    <ul>
                                        <li>Type (or paste) a text in the text area here below</li>
                                        <li>Press the Encode button</li>
                                    </ul>
                                </div>
                                <div class="col-sm">
                                    <img src="https://raw.githubusercontent.com/cavo789/json_encode/master/images/demo.gif" alt="Demo">
                                </div>
                            </div>
                        </div>
                    </details>
                    <label for="JSON">Copy/Paste your text in the 
                        textbox below then click on the Encode button:</label>
                    <textarea class="form-control" rows="5" id="JSON" name="JSON"><?php echo $JSON; ?></textarea>
                </div>
                <button type="button" id="btnEncode" class="btn btn-primary">Encode</button>
                <hr/>
                <pre id="Result"></pre>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
        <script type="text/javascript">
            $('#btnEncode').click(function(e)  {

                e.stopImmediatePropagation();

                var $data = new Object;
                $data.task = "encode";
                $data.json = window.btoa($('#JSON').val());

                $.ajax({
                    beforeSend: function() {
                        $('#Result').html('<div><span class="ajax_loading">&nbsp;</span><span style="font-style:italic;font-size:1.5em;">Formatting...</span></div>');
                        $('#btnEncode').prop("disabled", true);
                    },
                    async: true,
                    type: "POST",
                    url: "<?php echo basename(__FILE__); ?>",
                    data: $data,
                    datatype: "html",
                    success: function (data) {
                        $('#btnEncode').prop("disabled", false);
                        $('#Result').html(window.atob(data));
                    }
                });
            });
        </script>
    </body>
</html>
