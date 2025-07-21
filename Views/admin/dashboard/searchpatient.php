
			
			<!-- Page Wrapper -->
            <div class="page-wrapper">
                <div class="content container-fluid">
					
						<div class="row">
							<div class="col-12">
								
								<div class="search-result">
									<h3>Search Result Found For: <u><?php echo $_GET['keywords']; ?></u></h3>
									<p><span class="search-results"></span> Results found</p>
								</div>
								<input type="hidden" id="keywords" value="<?php echo $_GET['keywords']; ?>">
								
								<div class="search-lists">
									<ul class="nav nav-tabs nav-tabs-solid">
										<li class="nav-item"><a class="nav-link " href="<?php echo base_url();?>admin/dashboard/search?keywords=<?php echo $_GET['keywords'];?>" >Veterinarians</a></li>
										<li class="nav-item"><a class="nav-link active" href="#results_patients" data-toggle="tab">Patients</a></li>
									</ul>
									<div class="tab-content">
										
										

										
										<div class="tab-pane show active" id="results_patients">
											
											<input type="hidden" name="page" id="page_no_hidden" value="1" >
									<div class="row row-grid" id="patient-list">
										
										
									</div>
									<div class="spinner-border text-success text-center" role="status" id="loading"></div>
											<div class="load-more text-center d-none" id="load_more_btn">
								<a class="btn btn-primary btn-sm" href="javascript:void(0);">Load More</a>	
							</div>
							
										</div>
										
									</div>
								</div>
								
							</div>
						</div>
					
				</div>			
			</div>
			<!-- /Page Wrapper -->
		
        