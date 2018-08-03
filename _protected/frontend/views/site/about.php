<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = Yii::t('messages', 'About');
?>
<script type="text/javascript">
  jQuery(document).ready(function($){
    $('.booklist').css('display', 'none');
     });
</script>

<div class="site-about">
   <div class="card" style="word-break: break-all;">
        <div class="card-header" >
            <h2><?=Yii::t('messages', 'About Us')?> 
            </h2>
            <div class="short-description f-17">
                    Beach Club Ippocampo è una località del Comune di Manfredonia nella splendida regione Puglia. Con vista panoramica del Golfo di Manfredonia. A 27 km da San Giovanni Rotondo e la Chiesa di San Pio; a km. 30 dalla stazione di Foggia; a km. 82 dall’aeroporto di Bari.<br>Tutte le camere sono dotate bagno privato; aria condizionata e tv a schermo piatto. E’ possibile soggiornare con formula mezza pensione o pensione completa a partire da € 15,00 a pasto al giorno a persona.<br>Gli ospiti hanno a disposizione (gratuitamente): due piscine idromassaggio ed una piscina all’aperto; wifi. Nei pressi della struttura AcquaPark; windsurf, Kitesurf, cicloturismo, tennis, calcetto. <br>A pagamento, invece, possono usufruire in loco di: due ristoranti; un bar; noleggio bici; spiaggia privata. <br>I clienti della spiaggia possono prenotare e pagare da www.beachclubippocampo.rentals oppure cliccando sull’icona con lo splash, qui sotto, a sinistra delle altre icone (accanto a Facebook).<br>E’ possibile effettuare escursioni in barca per visitare le grotte del Gargano oppure con automezzi alle Grotte di Castellana e Trulli di Alberobello; Trani e Castel del Monte; San Giovanni Rotondo e Monte Sant’Angelo; le isole Tremiti con volo in elicottero. <br>Le opportunità culturali della zona: Canne della Battaglia (Antica Roma); gli Ipogei (Cristianità); le Stele Daune (Preistoria); le Chiese di S. Maria di Siponto e di San Leonardo (via Francigena); Monte Sant’Angelo (Chiesa Ortodossa); l’Oasi naturale del Lago Sal<br>
                </div>
        </div>
        <div class="card-body" >
            <div class="pmo-contact">        
                <ul class="fa-ul" >
                    <li ><a href="tel:<?=$settingInfo->phonenumber?>"><i class="fa-li fa fa-phone fa-lg"></i>  +<?=$settingInfo->phonenumber?></a></li>
                    <li ><a href="mailto:<?=$settingInfo->email?>"> <i class="fa-li fa fa-envelope fa-lg"></i><?=$settingInfo->email?></a></li>
                    <li ><a class="facebookAnchor" target="_blank" href="#"><i class="fa-li fa fa-facebook fa-lg" ></i> Beach Clup Ippocamp (facebook)</a></li>
                    <li ><a href="#" target="_blank"><i class="fa-li fa fa-twitter fa-lg"></i> Beach Clup Ippocamp (twitter)</a></li>
                    <li style="color: #337ab7;">
                        <i class="fa-li fa fa-location-arrow fa-lg" ></i>
                        <span >
                            Strada Provinciale 141 - Ippocampo<br>
                            Zapponeta, Apulia<br>
                            71043 Italy
                        </span>
                        
                    </li>
                </ul>
            </div>
            
            <div id="map"></div>
        </div>
    </div>
</div>

  <script>
    function initMap() {
        var myLatLng = {lat: 41.5086135, lng: 15.9154171};

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 15,
          center: myLatLng
        });

        var marker = new google.maps.Marker({
          position: myLatLng,
          map: map,
          title: 'Beach Club Ippocampo'
        });
      }
    </script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBJZWb5qT8irUJ8eWrAh2GzRfYy0YV9n6A&callback=initMap">
    </script>


