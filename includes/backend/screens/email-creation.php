    <!-- Breadcumb Sections -->
    <div class="breadcumb-section pt-0">
            <div class="cta-employee-single cta-banner mx-auto maxw1700 pt120 pt60-sm pb120 pb60-sm bdrs16 position-relative d-flex align-items-center">
                <img class="service-v1-vector at-job bounce-x d-none d-xl-block" src="images/vector-img/vector-service-v1.png" alt="">
                <div class="container">
                <div class="row wow fadeInUp">
                    <div class="col-xl-7">
                    <div class="position-relative">
                        <h2>Create A Custom Email Address</h2>
                        <p class="text">Create custom email addresses and make a great impression.</p>
                    </div>
                    <div class="advance-search-tab bgc-white p10 bdrs4 mt30">
                    <form class="form-search position-relative" id="email-creation">

                        <div class="row">
                        <div class="col-md-5 col-lg-6 col-xl-6">
                            <div class="advance-search-field bdrr1 bdrn-sm">
                                <div class="box-search bb1-sm">
                                <span class="icon far fa-envelope"></span>
                                <input class="form-control" type="text" name="domain-name" placeholder="e.g emmanuel">
                                <div class="search-suggestions">
                                    <h6 class="fz14 ml30 mt25 mb-3">Suggestions</h6>
                                    <div class="box-suggestions">
                                    
                                    </div>
                                </div>
                                </div>
                           
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-xl-3">
                            <div class="bselect-style1">
                                <select class="selectpicker" data-width="100%" name="domain-tld">
                                    <option value="<?php echo $domain_name;?>"><?php echo $domain_name;?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-2 col-xl-3">
                            <div class="text-center text-xl-start">
                            <button class="ud-btn btn-thm2 w-100 vam" type="submit" id="domain_load">Create Email</button>
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

            

            
            

            