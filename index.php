<?php
    //Koneksi Database
    $server = "localhost";
    $user = "root";
    $password = "";
    $database = "crud-inventaris";

    //Buat koneksi
    $koneksi = mysqli_connect($server, $user, $password, $database) or die(mysqli_error($koneksi));

    //Kode Otomatis
    $q = mysqli_query($koneksi, "SELECT kode FROM tbarang order by kode desc limit 1");
    $datax = mysqli_fetch_array($q);
    if ($datax) {
        $no_terakhir = substr($datax['kode'], -3);
        $no = $no_terakhir + 1;

        if ($no > 0 and $no < 10) {
            $kode = "00".$no;
        } else if($no > 10 and $no < 100) {
            $kode = "0".$no;
        } else if($no > 100) {
            $kode = $no;
        }
    } else {
        $kode = "001";
    }

    $tahun = date('Y');
    $vkode = "IVN-" . $tahun. "-" . $kode;

    //Simpan
    if(isset($_POST['bsimpan'])){

        //Data Edit dan Data Hapus
        if (isset($_GET['hal']) == "edit") {
            //Data diedit
            $edit = mysqli_query($koneksi, "UPDATE tbarang SET
                                                   nama = '$_POST[tnama]',
                                                   asal = '$_POST[tasal]',
                                                   jumlah = '$_POST[tjumlah]',
                                                   satuan = '$_POST[tsatuan]',
                                                   tanggal_diterima = '$_POST[ttanggal_diterima]'
                                            WHERE idbarang = '$_GET[id]'
                                           ");

            //Notifikasi Data Disimpan
            if($edit){
                echo"<script>
                        alert('Edit data sukses');
                        document.location='index.php';
                    </script>";
                }else {
                    echo"<script>
                            alert('Edit data sukses');
                            document.location='index.php';
                        </script>";   
            } 
        } else {
            //Data akan disimpan baru
            $simpan = mysqli_query($koneksi, "INSERT INTO tbarang (kode, nama, asal, jumlah, satuan, tanggal_diterima)
                                              VALUE ( '$_POST[tkode]',
                                                      '$_POST[tnama]',
                                                      '$_POST[tasal]',
                                                      '$_POST[tjumlah]',
                                                      '$_POST[tsatuan]',
                                                      '$_POST[ttanggal_diterima]'
                                                    )
                                             ");
            //Notifikasi Data Disimpan
            if($simpan){
                echo"<script>
                        alert('Simpan data sukses');
                        document.location='index.php';
                    </script>";
                }else {
                    echo"<script>
                            alert('Simpan data sukses');
                            document.location='index.php';
                        </script>";  
            }
        }
    }

    //Deklarasi Edit
    $vnama = "";
    $vasal = "";
    $vjumlah = "";
    $vsatuan = "";
    $vtanggal_diterima = "";

    //Edit Data
    if (isset($_GET['hal'])) {
        //Mengedit Data
        if ($_GET['hal'] == "edit") {
            //Tampil data yang akan diedit
            $tampil = mysqli_query($koneksi, "SELECT * FROM tbarang WHERE idbarang = '$_GET[id]' ");
            $data = mysqli_fetch_array($tampil);
            if ($data) {
                //Data ditampung dalam variabel
                $vkode = $data['kode'];
                $vnama = $data['nama'];
                $vasal = $data['asal'];
                $vjumlah = $data['jumlah'];
                $vsatuan = $data['satuan'];
                $vtanggal_diterima = $data['tanggal_diterima'];
            }
        } else if ($_GET['hal'] == "hapus") {
            //Hapus data
            $hapus = mysqli_query($koneksi, "DELETE FROM tbarang WHERE idbarang = '$_GET[id]'");
            //Notifikasi Data Dihapus
            if($hapus){
                echo"<script>
                        alert('Hapus data sukses');
                        document.location='index.php';
                    </script>";
                }else {
                    echo"<script>
                            alert('Hapus data sukses');
                            document.location='index.php';
                        </script>";  
            }
        }
    }
?>

<!doctype html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventaris Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
  </head>

  <body>
    <!--Awal Container-->
    <div class="container">
        <h3 class="text-center">Data Inventaris</h3>
        <h3 class="text-center">Kantor Desa Karangtalok</h3>
        <!--Awal Row-->
        <div class="row">
            <!--Awal Col-->
            <div class="col-md-8 mx-auto">
                <!--Awal Card-->
                <div class="card">
                    <div class="card-header bg-black text-light">
                        From Input Data Barang
                    </div>
                    <div class="card-body">
                        <!--Awal Form-->
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Kode Barang</label>
                                <input type="text" name="tkode" value="<?=$vkode?>" class="form-control" placeholder="Masukan Kode Barang">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Nama Barang</label>
                                <input type="text" name="tnama" value="<?=$vnama?>" class="form-control" placeholder="Masukan Nama Barang">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Asal Barang</label>
                                    <select class="form-select" name="tasal" value="<?=$vasal?>">
                                        <option value="<?=$vasal?>"><?=$vasal?></option>
                                        <option value="Pembelian">Pembelian</option>
                                        <option value="Hibah">Hibah</option>
                                        <option value="Bantuan">Bantuan</option>
                                        <option value="Sumbangan">Sumbangan</option>
                                    </select>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label class="form-label">Jumlah</label>
                                        <input type="number" name="tjumlah" value="<?=$vjumlah?>" class="form-control" placeholder="Masukan Jumlah Barang">
                                    </div>
                                </div>
                                
                                <div class="col">
                                    <div class="mb-3">
                                        <label class="form-label">Satuan</label>
                                            <select class="form-select" name="tsatuan" >
                                                <option value="<?=$vsatuan?>"><?=$vsatuan?></option>
                                                <option value="Unit">Unit</option>
                                                <option value="Kotak">Kotak</option>
                                                <option value="Pcs">Pcs</option>
                                                <option value="Pak">Pak</option>
                                            </select>
                                    </div>
                                </div>
                                
                                <div class="col">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Diterima</label>
                                        <input type="date" name="ttanggal_diterima" value="<?=$vtanggal_diterima?>" class="form-control" placeholder="Tanggal">
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button class="btn btn-primary" name="bsimpan" type="submit">Simpan</button>
                                    <button class="btn btn-danger" name="bkosongkan" type="reset">Kosongkan</button>
                                    <hr>
                                </div>

                                
                            </div>
                        </form>
                        <!--Akhir Form-->
                    </div>
                        <div class="card-footer bg-black">
                            
                        </div>
                </div>
                <!--Akhir Card-->
            <!--Akhir Col-->
        </div>
        <!--Akhir Row-->

        <!--Awal Card 2-->
        <div class="row">
            <div class="col mx-auto">
                <div class="card mt-3">
                    <div class="card-header bg-black text-light">
                        Data Barang
                    </div> 
                    <div class="card-body">
                        <div class="col-md-6 mx-auto">
                            <form method="POST">
                                <div class="input-group mb-3">
                                    <input type="text" name="tcari"  value="<?= @$_POST['tcari'] ?>" class="form-control" placeholder="Masukan Kata Kunci">
                                    <button class="btn btn-primary" name="bcari" type="submit">Cari</button>
                                    <button class="btn btn-danger" name="breset" type="submit">Reset</button>
                                </div>
                            </form>
                        </div>
                        <table class="table table-striped table-hover table-bordered">
                            <tr>
                                <th>No.</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Asal Barang</th>
                                <th>Jumlah</th>
                                <th>Tanggal Diterima</th>
                                <th>Aksi</th>
                            </tr>

                            <?php
                                //Menampilkan Data
                                $no = 1;
                                //Untuk pencarian data 
                                if (isset($_POST['bcari'])) {
                                    $keyword = $_POST['tcari'];
                                    $q = "SELECT * FROM tbarang WHERE kode like '%$keyword%' or nama like '%$keyword%' or asal like '%$keyword%' order by idbarang desc";
                                } else {
                                    $q = "SELECT * FROM tbarang order by idbarang desc";
                                }

                                $tampil = mysqli_query($koneksi, $q);
                                while($data = mysqli_fetch_array($tampil)) : 
                            ?>

                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $data['kode'] ?></td>
                                <td><?= $data['nama'] ?></td>
                                <td><?= $data['asal'] ?></td>
                                <td><?= $data['jumlah'] ?> <?= $data['satuan'] ?></td>
                                <td><?= $data['tanggal_diterima'] ?></td>
                                <td>
                                    <a href="index.php?hal=edit&id=<?= $data['idbarang']?>" class="btn btn-warning">Edit</a>

                                    <a href="index.php?hal=hapus&id=<?= $data['idbarang']?>" class="btn btn-danger" onclick="return confirm('Apakah anda yakin akan hapus data ini?')">Hapus</a>
                                </td>
                            </tr>

                            <?php endwhile; ?>

                        </table>

                    </div>
                    <div class="card-footer bg-black">
                                    
                    </div>
                </div>

            </div>

        </div>
        <!--Akhir Card 2-->
    
        
        
    </div>
    <!--Akhir Container-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
  </body>

</html>