       <div class="contact-info-wrap col-md-6 col-12 mb-40">
           <h3>Bize Ulaşın</h3>
           <p>Jadusona, elitler için en iyi temadır, ancak zaman zaman çalışırken acı verici bir şekilde işlemek gerekir, ancak minimum nezaketle.</p>
           <ul class="contact-info">
               <li>
                   <i class="fa fa-map-marker"></i>
                   <p><?= $Adres ?></p>
               </li>
               <li>
                   <i class="fa fa-phone"></i>
                   <p><a href="#"><?= $Telefon1 ?></a><a href="#"><?= $Telefon1 ?></a></p>
                   <p><a href="#"><?= $Telefon2 ?></a><a href="#"><?= $Telefon2 ?></a></p>
               </li>
               <li>
                   <i class="fa fa-globe"></i>
                   <p><a href="#"><?= $Eposta1 ?></a><a href="#"><?= $Eposta1 ?></a></p>
                   <p><a href="#"><?= $Eposta2 ?></a><a href="#"><?= $Eposta2 ?></a></p>
               </li>
           </ul>
       </div>

       <div class="contact-form-wrap col-md-6 col-12 mb-40">
           <h3>Bir Mesaj Bırakın</h3>
           <form  id="contact-form" action="https://whizthemes.com/mail-php/other/mail.php" method="post">
               <div class="contact-form">
                   <div class="row">
                       <div class="col-lg-6 col-12 mb-30"><input type="text" name="con_name" placeholder="Adınız"></div>
                       <div class="col-lg-6 col-12 mb-30"><input type="email" name="con_email" placeholder="E-posta Adresiniz"></div>
                       <div class="col-12 mb-30"><textarea name="con_message" placeholder="Mesajınız"></textarea></div>
                       <div class="col-12"><input type="submit" value="Gönder"></div>
                   </div>
               </div>
           </form>
           <div class="form-message mt-3"></div>
       </div>

    </div>
</div>
