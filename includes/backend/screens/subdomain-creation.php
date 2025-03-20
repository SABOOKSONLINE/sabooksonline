 <!-- Breadcumb Sections -->
 <div class="breadcumb-section pt-0">
            <div class="cta-employee-single cta-banner mx-auto maxw1700 pt120 pt60-sm pb120 pb60-sm bdrs16 position-relative d-flex align-items-center">
                <img class="service-v1-vector at-job bounce-x d-none d-xl-block" src="images/vector-img/vector-service-v1.png" alt="">
                <div class="container">
                <div class="row wow fadeInUp">
                    <div class="col-xl-7">
                    <div class="position-relative">
                        <h2>Generate Your Free Domain</h2>
                        <p class="text">Create your website today and use the SA Books domain for free.</p>
                    </div>
                    <div class="advance-search-tab bgc-white p10 bdrs4 mt30">
                    <form class="form-search position-relative" id="domain-search">

                        <div class="row">
                        <div class="col-md-5 col-lg-6 col-xl-5">
                            <div class="advance-search-field bdrr1 bdrn-sm">
                                <div class="box-search bb1-sm">
                                <span class="icon fas fa-globe"></span>
                                <input class="form-control" type="text" name="domain-name" placeholder="yourdomain" value="<?php
                                    if ($_SESSION['ADMIN_NAME']) {

                                        $domain = $_SESSION['ADMIN_NAME'];
                                        $domain = strtolower(str_replace(' ','-', $domain));
                                        $domain = strtolower(str_replace('.','-', $domain));

                                        $domain = preg_replace('/[^a-zA-Z0-9\s-]/', '', $domain);

                                        echo $domain;
                                    }
                                    ?>" disabled="true">
                                </div>
                           
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-xl-3">
                            <div class="bselect-style1">
                                <select class="selectpicker" data-width="100%" name="domain-tld">
                                    <option value="sabooksonline.co.za">sabooksonline.co.za</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-2 col-xl-4">
                            <div class="text-center text-xl-start">
                            <a href="create?type=sub&domain=<?php echo $domain;?>" class="ud-btn btn-thm2 w-100 vam" type="submit">Create Website</a>
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

            

            