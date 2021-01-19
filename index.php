<?php include 'header.php';?>
<?php
  require_once "config.php";
  $timezone = "Asia/Jakarta";
  date_default_timezone_set($timezone);
  $today = date("Y-m-d");

  $rekomendasikamar = mysqli_query($con, "SELECT * FROM tbl_kamar WHERE id=2");
  $datarekomendasikamar = mysqli_fetch_array($rekomendasikamar);
  $namakamar = $datarekomendasikamar[1];
  $hargakamar = $datarekomendasikamar[2];
  $kamartersedia = $datarekomendasikamar[3];

  if(isset($_POST['submit']))
    {

        $idpelanggan = mysqli_real_escape_string($con, $_SESSION['id']);
        $totalbayar = mysqli_real_escape_string($con, $_POST['totalbayar']);
        $checkin = date("Y-m-d", strtotime($_POST['checkin']));
        $checkout = date("Y-m-d", strtotime($_POST['tglcheckout']));
        $kamar = mysqli_real_escape_string($con, $_POST['kamar']);
        $kebutuhan = mysqli_real_escape_string($con, $_POST['kebutuhan']);
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $notlp = mysqli_real_escape_string($con, $_POST['notlp']);

        $sisa=$kamartersedia-$kamar;

        $booking = mysqli_query($con, "INSERT INTO tbl_booking (id_kamar,id_pelanggan, totalbayar, checkin, checkout, jumlah_kamar, kebutuhan_tambahan,email,notlp) VALUES 
        ('2','".$idpelanggan."','".$totalbayar."','".$checkin."','".$checkout."','".$kamar."','".$kebutuhan."','".$email."','".$notlp."')");

        if($booking == true){
            $upstok= mysqli_query($con, "UPDATE tbl_kamar SET jumlah_kamar='$sisa' WHERE id=2");
            echo "<script>alert('Pemesanan Berhasil!!!');
            window.location.href = 'pelanggan.php';</script>";
        }else{
            echo "<script>alert('Pemesanan Gagal!!!');
            window.location.href = 'index.php';</script>";
        }
    }
?>
<script type="text/javascript">
    function checkout()
    {
        var tt = document.form.checkin.value;
        var date = new Date(tt);
        var newdate = new Date(date);
        var malam = parseInt(document.form.malam.value);
        newdate.setDate(date.getDate() + malam);
        
        var dd = newdate.getDate();
        var mm = newdate.getMonth() + 1;
        var y = newdate.getFullYear();

        var someFormattedDate = mm + '/' + dd + '/' + y;
        document.form.tglcheckout.value = someFormattedDate;

        var hargakamar = parseInt(document.form.hargakamar.value);
        var totalbayar = 0;
        var permalam = 0;
        var perkamar = 0;
        var kamar = parseInt(document.form.kamar.value);
        var tamu = 2;
        if(malam>1){ 
            for(var i=0;i<malam;i++){
                permalam+=hargakamar;
            };
        }else{ permalam=hargakamar;}

        if(kamar>1){ 
            for(var i=0;i<kamar;i++){
                perkamar+=hargakamar;
            };
        }else{ perkamar=hargakamar;}

        if(malam==1 && kamar==1){
            totalbayar = hargakamar;
        }else if(malam==1){
            totalbayar = perkamar;
        }else if(kamar==1){
            totalbayar = permalam;
        }
        else{ totalbayar=permalam+perkamar;}

        if(kamar > 1){
            for(var i=1;i<kamar;i++){
                tamu+=tamu;
            };
            document.form.jumlahtamu.value = tamu;
        }else{document.form.jumlahtamu.value = tamu;}
        
        document.form.totalbayar.value = totalbayar;
    }
</script>
<!-- banner -->
<div class="banner">    	   
    <img src="images/photos/banner.jpg"  class="img-responsive" alt="slide">
    <div class="welcome-message">
        <div class="wrap-info">
            <div class="information">
                <h1  class="animated fadeInDown">Hotel terbaik di Jawa</h1>
                <p class="animated fadeInUp"></p>                
            </div>
            <a href="#information" class="arrow-nav scroll wowload fadeInDownBig"><i class="fa fa-angle-down"></i></a>
        </div>
    </div>
</div>
<!-- banner-->


<!-- reservation-information -->
<div id="information" class="spacer reserve-info ">
<div class="container">
<div class="row">
<div class="col-sm-7 col-md-8 wowload fadeInUp"><div class="rooms"><img src="images/photos/8.jpg" class="img-responsive">
<div class="info"><h3><?php echo $namakamar;?> adalah Rekomendasi kamar bulan ini</h3><p> Hanya dengan harga  <?php echo $hargakamar;?> permalamnya guys, Yuk buruan booking guys hanya tinggal sisa <?php echo $kamartersedia;?> kamar lagi nihh</p></div></div></div>
<div class="col-sm-5 col-md-4">
<h3>Reservasi sekarang</h3>
    <form name="form" role="form" class="wowload fadeInRight" method="POST" action="index.php">
        <div class="form-group">
            <input type="text" class="form-control"  placeholder="Nama" value="<?php echo $nama;?>"  readonly>
            <input name="nim1" type="hidden" id="hargakamar" value="<?php echo $hargakamar; ?>">
        </div>
        <div class="form-group">
            <input type="email" class="form-control"  placeholder="Email" name="email" value="<?php echo $datanama[4];?>">
        </div>
        <div class="form-group">
            <input type="Phone" class="form-control"  placeholder="No Hp" name="notlp" value="<?php echo $datanama[3];?>">
        </div>
        <div class="form-group">
            <div class="row">  
            <div class="col-xs-6">
            <h5>Tanggal Check-in</h5> <input class="form-control" id="checkin" name="checkin" value="<?php echo $today; ?>" type="date"/>
            </div>
            <div class="col-xs-6">
            <h5>Malam</h5> 
            <select class="form-control" id="malam" onclick="checkout();">
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
            </select>
            </div></div>
        </div>        
        <div class="form-group">
            <div class="row">
            <div class="col-xs-6">
            <h5>Jumlah kamar</h5> 
            <select class="form-control" id="kamar" name="kamar" onchange="checkout();">
            <?php
                $tersedia=1;
                for($i=1;$i<=$kamartersedia;$i++){
                    echo "<option value=".$i.">".$i."</option>";
                };
                if($kamartersedia==0){
                    echo "<option>Kamar penuh</option>";
                    $tersedia=0;
                }
            ?>
            </select>
            </div>        
            <div class="col-xs-6">
            <h5>Jumlah Maksimal Tamu</h5> 
            <input type="text" class="form-control" id="jumlahtamu"  placeholder="2" disabled/>
        </div>
            </div>
        </div>
        <div class="form-group">
            <textarea class="form-control"  placeholder="Kebutuhan Tambahan" name="kebutuhan" rows="4"></textarea>
        </div>
        <div class="form-group">
            <input type="Phone" class="form-control" id="tglcheckout" name="tglcheckout" placeholder="Tanggal Check-Out" readonly>
        </div>
        <div class="form-group">
            <input type="Phone" class="form-control" id="totalbayar" name="totalbayar"  placeholder="Total Bayar" readonly>
        </div>
        <?php
		if ($tersedia==0)
		{
            ?>
            <p class="btn btn-default">Kamar Penuh</p>
                <?php
        }else if(@$_SESSION['id'] == true){
            ?>
            <button class="btn btn-default" name="submit" onclick="return confirm('Apakah Data Pemesanan Anda Sudah Benar?')">Submit</button>
            <?php
        }
        else{
            ?>
            <a class="btn btn-default" href="login.php">Login untuk reservasi</a>
            <?php
        }
        ?>
    </form>    
</div>
</div>  
</div>
</div>
<!-- reservation-information -->



<!-- services -->
<div class="spacer services wowload fadeInUp">
<div class="container">
    <div class="row">
        <div class="col-sm-4">
            <!-- RoomCarousel -->
            <div id="RoomCarousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                <div class="item active"><img src="images/photos/8.jpg" class="img-responsive" alt="slide"></div>                
                <div class="item  height-full"><img src="images/photos/9.jpg"  class="img-responsive" alt="slide"></div>
                <div class="item  height-full"><img src="images/photos/10.jpg"  class="img-responsive" alt="slide"></div>
                </div>
                <!-- Controls -->
                <a class="left carousel-control" href="#RoomCarousel" role="button" data-slide="prev"><i class="fa fa-angle-left"></i></a>
                <a class="right carousel-control" href="#RoomCarousel" role="button" data-slide="next"><i class="fa fa-angle-right"></i></a>
            </div>
            <!-- RoomCarousel-->
            <div class="caption">Rooms<a href="rooms-tariff.php" class="pull-right"><i class="fa fa-edit"></i></a></div>
        </div>


        <div class="col-sm-4">
            <!-- RoomCarousel -->
            <div id="TourCarousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                <div class="item active"><img src="images/photos/6.jpg" class="img-responsive" alt="slide"></div>
                <div class="item  height-full"><img src="images/photos/3.jpg"  class="img-responsive" alt="slide"></div>
                <div class="item  height-full"><img src="images/photos/4.jpg"  class="img-responsive" alt="slide"></div>
                </div>
                <!-- Controls -->
                <a class="left carousel-control" href="#TourCarousel" role="button" data-slide="prev"><i class="fa fa-angle-left"></i></a>
                <a class="right carousel-control" href="#TourCarousel" role="button" data-slide="next"><i class="fa fa-angle-right"></i></a>
            </div>
            <!-- RoomCarousel-->
            <div class="caption">Tour Packages<a href="gallery.php" class="pull-right"><i class="fa fa-edit"></i></a></div>
        </div>


        <div class="col-sm-4">
            <!-- RoomCarousel -->
            <div id="FoodCarousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                <div class="item active"><img src="images/photos/1.jpg" class="img-responsive" alt="slide"></div>
                <div class="item  height-full"><img src="images/photos/2.jpg"  class="img-responsive" alt="slide"></div>
                <div class="item  height-full"><img src="images/photos/5.jpg"  class="img-responsive" alt="slide"></div>
                </div>
                <!-- Controls -->
                <a class="left carousel-control" href="#FoodCarousel" role="button" data-slide="prev"><i class="fa fa-angle-left"></i></a>
                <a class="right carousel-control" href="#FoodCarousel" role="button" data-slide="next"><i class="fa fa-angle-right"></i></a>
            </div>
            <!-- RoomCarousel-->
            <div class="caption">Food and Drinks<a href="gallery.php" class="pull-right"><i class="fa fa-edit"></i></a></div>
        </div>
    </div>
</div>
</div>
<!-- services -->


<?php include 'footer.php';?>