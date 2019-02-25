<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <title>Accueil</title>
</head>
<body>
    <div class="container">
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script>
        function showFiles(curentDirectory, files) {
            var container = $("div.container");
            container.empty();
            container.append("<a href=\"#\" data-target=\"" + encodeURI(curentDirectory + "/..") + "\"><div class=\"item\"><i class=\"fas fa-folder fa-4x\"></i><br/>Dossier parent</div></a>");
            files.forEach(function(element) {
                if(element.type == "file") {
                    container.append("<a href=\"" +  element.name + "\"><div class=\"item\"><i class=\"" + element.classIcon + " fa-4x\"></i><br/>" + element.name + "</div></a>");
                } else {
                    container.append("<a href=\"#\" data-target=\"" + encodeURI(element.name) + "\"><div class=\"item\"><i class=\"" + element.classIcon + " fa-4x\"></i><br/>" + element.name + "</div></a>");
                }
            });
        }

        function showFolder(currentDirectory, targetDirectory) {
            $.ajax({
                url: "api.php",
                data: { query: "getFormatedContentFromDirectory", currentDirectory: currentDirectory, targetDirectory: targetDirectory },
                success: function(data) {
                    showFiles(currentDirectory, data);
                }
            });
        }

        $(function() {
            var currentFolder = ".";
            showFolder(currentFolder, ".");

            $(document).on('click', 'a', function() {
                currentFolder = $(this).data("target");
                showFolder(currentFolder, $(this).data("target"));
            })

        });
    </script>
</body>
</html>