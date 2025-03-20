
<div class=""><hr></div>
<div class="col-lg-12" id="books" style="display: flex !important;justify-content: flex-start !important;flex-wrap: wrap !important;">

						<?php
                                                //DATABASE CONNECTIONS SCRIPT
                                                include './database_connections/sabooks.php';
                                               // $sql = "SELECT * FROM posts WHERE STATUS = 'active' ORDER BY RAND();";

											   if(empty($_GET['creator'])){
												$create = "";
											   }else {
												$creator = $_GET['creator'];

												if($creator == 'all' || $creator == 'All'){
													$create = "";
												} else {
													$create = " AND TYPE = '$creator'";
												}

											   }
											   

											   if(empty($_GET['sort'])){
												$sort = '';
											   }else {
												$sort = str_replace(' ', '-', $_GET['sort']);
											   }


											   $category_query = "";

											   if(empty($_GET['category'])){
												$category = '';
											   } else {
												$category = $_GET['category'];

												$category = str_replace(',', '|', $category);
											   	$category = str_replace('-', ' ', $category);
											   	$category = str_replace('and', '&', $category);
											   	$category = str_replace('_', ',', $category);

												$category_query = " AND CATEGORY LIKE '%$category%'";

											   }


											   $category_keywords = "";

											   if(empty($_GET['key'])){
												$category_keywords = '';
											   } else {
												$keywords = $_GET['key'];

												$keywords = str_replace('-','|', $keywords);
												//$keywords_2 = str_replace(' ','|', $keywords);

												$category_keywords = " AND CATEGORY LIKE '%$keywords%' OR PUBLISHER REGEXP '$keywords'  OR TITLE REGEXP '$keywords' ";

											   }


											   //EXACT RESULTS FILLTER

											   if(empty($_GET['key'])){

											   } else {

											   $keyplain = str_replace('|', ' ', $keywords);

											   $sql = "SELECT * FROM posts WHERE STATUS = 'active' AND TITLE = '$keyplain';";
                                                $result = mysqli_query($conn, $sql);
                                                    if(mysqli_num_rows($result) == false){
														
                                                    }else{


														if(!empty($keywords)){
															echo '
															<h6>Exact results for <b>"'.ucwords(str_replace('|', ' ', $keywords)).'"</b></h6><div class="container"><hr></div>';
														}
														

														//echo $sql;
                                                    while($row = mysqli_fetch_assoc($result)) {

														$username = ucwords(substr($row['PUBLISHER'], '0', '20'));

                                                        echo '

														<div class="col-lg-12 d-flex justify-content-start p-0 m-0 p-3 mb-5 main-display" style="background-color: #f3f3f3;border-radius: 10px;">
															<div class="strip strip-books p-0 m-0">
															<a href="book?q='.strtolower($row['TITLE']).'" class="text-dark head-t"><figure>
																	<span class="ribbon off">'.$row['CATEGORY'].'</span>
																	<img src="https://my.sabooksonline.co.zacms-data/book-covers/'.$row['COVER'].'" data-src="https://my.sabooksonline.co.zacms-data/book-covers/'.$row['COVER'].'" class="img-fluid lazy" alt="" >
																</figure></a>
															</div>

															<div class="p-3">
																<h3><a href="book?q='.strtolower($row['CONTENTID']).'">'.$row['TITLE'].'</a></h3>
																Published By - <a href="creator?q='.strtolower($row['USERID']).'">'.ucfirst($row['PUBLISHER']).'</a> <small class="icon_check_alt text-success" style="font-size:12px"></small>

																<p class="mt-3">'.substr($row['DESCRIPTION'], 0, 400).'...</p>
															</div>
														</div>';
                                                    }
                                                }

											}

												//EXACT RESULTS FILTER
											   
											   

												$sql = "SELECT * FROM posts WHERE STATUS = 'active' $create $category_query $category_keywords ORDER BY RAND()";
                                                $result = mysqli_query($conn, $sql);
                                                    if(mysqli_num_rows($result) == false){
														echo '<div class="alert alert-warning border-none" style="border: none !important;">No content was found for your query.</div>';
                                                    }else{


														if(!empty($keywords)){
															echo '
															<h6 >Related Search results for <b>"'.ucwords(str_replace('|', ' ', $keywords)).'"</b></h6><div class="container"><hr></div>';
														}
														

														//echo $sql;
                                                    while($row = mysqli_fetch_assoc($result)) {

														$username = ucwords(substr($row['PUBLISHER'], '0', '20'));

                                                        echo '
														<div class="strip strip-books col-sm-6 p-1 mt-2" id="booklisting" style="width:100px !important;height: 150px !important;">
														<a href="book?q='.strtolower($row['CONTENTID']).'" class="text-dark head-t">
																<span class="ribbon off">'.$row['CATEGORY'].'</span>
																<img src="https://my.sabooksonline.co.za/cms-data/book-covers/'.$row['COVER'].'" data-src="cms-data/book-covers/'.$row['COVER'].'" class="img-fluid lazy" alt="" width="100px">
															</a>

                                                            <a href="creator?q='.strtolower($row['USERID']).'" class="text-dark head-t"T'.ucwords($row['PUBLISHER']).' <small class="icon_check_alt text-success" style="font-size:12px"></small></a>
															<p class="mt-1 head-p"><a href="book?q='.strtolower($row['CONTENTID']).'"><i class="icon_link"></i> '.substr($row['TITLE'], 0, 50).'</a></p>
															
														</div>';
                                                    }
                                                }
                                            ?>


											</div>
					