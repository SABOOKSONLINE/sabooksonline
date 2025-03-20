 <!-- Breadcumb Sections -->
 <div class="breadcumb-section pt-0">
            <div class="cta-employee-single cta-banner mx-auto maxw1700 pt120 pt60-sm pb120 pb60-sm bdrs16 position-relative d-flex align-items-center">
                <img class="service-v1-vector at-job bounce-x d-none d-xl-block" src="images/vector-img/vector-service-v1.png" alt="">
                <div class="container">
                <div class="row wow fadeInUp">
                    <div class="col-xl-7">
                    <div class="position-relative">
                        <h2>Register Your Domain</h2>
                        <p class="text">Standout & Register your domain today with SA Books Online!</p>
                    </div>
                    <div class="advance-search-tab bgc-white p10 bdrs4 mt30">
                    <form class="form-search position-relative" id="domain-search">

                        <div class="row">
                        <div class="col-md-5 col-lg-6 col-xl-6">
                            <div class="advance-search-field bdrr1 bdrn-sm">
                                <div class="box-search bb1-sm">
                                <span class="icon far fa-magnifying-glass"id="domain-type"></span>
                                <input class="form-control" type="text" name="domain-name" placeholder="yourdomain">
                                <div class="search-suggestions">
                                    <h6 class="fz14 ml30 mt25 mb-3">Suggestions</h6>
                                    <div class="box-suggestions">
                                    <ul class="px-0 m-0 pb-4">

                                    <?php
                                    if ($_SESSION['ADMIN_NAME']) {
                                        $name = $_SESSION['ADMIN_NAME'];
                                        $name = strtolower(str_replace(' ', '-', $name));
                                        $extensions = array(".co.za", ".org.za", ".org", ".africa", ".com"); // Add more extensions if needed
                                        $suggestions = array();

                                        foreach ($extensions as $extension) {
                                            $suggestions[] = $name . $extension;
                                        }
                                    }
                                    ?>

                              
                                    <?php foreach ($suggestions as $suggestion) : ?>
                                        <li>
                                            <div class="info-product">
                                                <div class="item_title"><?php echo $suggestion; ?></div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                     
                                    </ul>
                                    </div>
                                </div>
                                </div>
                           
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-xl-3">
                            <div class="bselect-style1">
                                <select class="selectpicker" data-width="100%" name="domain-tld">
                                    <?php

                                    if($_SESSION['ADMIN_SUBSCRIPTION'] == 'Free'){
                                    echo '<option value="co.za">.co.za</option>
                                    <option value="org.za">.org.za</option>';
                                    }elseif($_SESSION['ADMIN_SUBSCRIPTION'] == 'Standard'){
                                    echo '<option value="co.za">.co.za</option>
                                    <option value="org.za">.org.za</option>';
                                    }elseif($_SESSION['ADMIN_SUBSCRIPTION'] == 'Premium'){
                                    echo '<option value="co.za">.co.za</option>
                                    <option value="org.za">.org.za</option>
                                    <option value="com">.com</option>
                                    <option value="org">.org</option>';
                                    }elseif($_SESSION['ADMIN_SUBSCRIPTION'] == 'Deluxe'){
                                    echo '<option value="co.za">.co.za</option>
                                    <option value="org.za">.org.za</option>
                                    <option value="com">.com</option>
                                    <option value="org">.org</option>';
                                    }

                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-2 col-xl-3">
                            <div class="text-center text-xl-start">
                            <button class="ud-btn btn-thm2 w-100 vam" type="submit" id="domain_load">Search</button>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                    </form>
                </div>
                </div>
            </div>
            </div>



            <div class="col-lg-12">
            <div class="ui-content">
              <div class="message-alart-style1">
                <div id="domain_status" class="list-style1"></div>
              </div>
            </div>
          </div>

            

            