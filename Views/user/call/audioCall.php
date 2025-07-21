<!DOCTYPE html> 
<html lang="en">
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<title><?php echo !empty(settings("meta_title"))?settings("meta_title"):"Doccure";?></title>
	<meta content="<?php echo !empty(settings("meta_keywords"))?settings("meta_keywords"):"";?>" name="keywords">
	<meta content="<?php echo !empty(settings("meta_description"))?settings("meta_description"):"";?>" name="description">
	<!-- Favicons -->
	<link href="<?php echo !empty(base_url().settings("favicon"))?base_url().settings("favicon"):base_url()."assets/img/favicon.png";?>" rel="icon">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.min.css">

	<!-- Fontawesome CSS -->
	<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/fontawesome/css/fontawesome.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/fontawesome/css/all.min.css">

	<!-- Main CSS -->
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/style.css">

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="assets/js/html5shiv.min.js"></script>
<script src="assets/js/respond.min.js"></script>
<![endif]-->

</head>
<body class="call-page video-page">

	<!-- Main Wrapper -->
	<div class="main-wrapper">



		<!-- Page Content -->
		<div class="content">

			<!-- Call Wrapper -->
			<div class="call-wrapper">
				<div class="call-main-row">
					<div class="call-main-wrapper">
						<div class="call-view">
							<div class="call-window">

								<?php
								/** @var string $role */
								/** @var array $appoinments_details */

								$profileimage=base_url().'assets/img/user.png';
				
								if($role=='doctor')
								{ 									
									$name ='Dr. '.ucfirst(libsodiumDecrypt($appoinments_details['doctor_name']));
									if (!empty($appoinments_details['doctor_profileimage']) && file_exists($appoinments_details['doctor_profileimage'])) {
										$profileimage = base_url().$appoinments_details['doctor_profileimage'];
									}
								}
								if($role=='patient')
								{
									$name =ucfirst(libsodiumDecrypt($appoinments_details['patient_name']));

									if (!empty($appoinments_details['patient_profileimage']) && file_exists($appoinments_details['patient_profileimage'])) {
										$profileimage = base_url().$appoinments_details['patient_profileimage'];
									}
								}

								?>

								<!-- Call Header -->
								<div class="fixed-header">
									<div class="navbar">
										<div class="user-details">
											<div class="float-left user-img">
												<a class="avatar avatar-sm mr-2" href="#" title="Charlene Reed">
													<img src="<?php 
                                                    /** @var string $profileimage */
													echo $profileimage;?>" alt="User Image" class="rounded-circle">
													<span class="status online"></span>
												</a>
											</div>
											<div class="user-info float-left">
												<a href="#"><span><?php 
                                                  /** @var string $name */
												echo $name;?></span></a>

											</div>
										</div>
										<div class="user-details float-right">
											<p id="time"></p>
										</div>
									</div>
								</div>
								<!-- /Call Header -->

								<!-- Call Contents -->
								<div class="call-contents">
									<div class="call-content-wrap">
										<div class="user-video">
											<div id="subscriber"></div>
										</div>
										<div class="my-video">
											<div id="publisher"></div>
										</div>
									</div>
								</div>
								<!-- Call Contents -->

								<!-- Call Footer -->
								<div class="call-footer">
									<div class="call-icons">

										<ul class="call-items">
											<li class="call-item" style="width: 100%; height: 30px; display: block;">
												<a href="javascript:void(0);" style="display: none; width: 170px;" id="startCaption" title="Start Caption" data-placement="top" data-toggle="tooltip">
													Start Caption
												</a>

												<a href="javascript:void(0);"  style="width: 170px;" id="stopCaption" title="Stop Caption" data-placement="top" data-toggle="tooltip">
													Stop Caption
												</a>

											</li>
											<li class="call-item" style="width: 100%; height: 30px; display: block;">
												<a href="javascript:void(0);" id="audio_enable" title="Enable Audio" data-placement="top" data-toggle="tooltip">
													<i class="fas fa-microphone-slash"></i>
												</a>
												
												<a href="javascript:void(0);" id="audio_disable" title="Disable Audio" data-placement="top" data-toggle="tooltip">
													<i class="fas fa-microphone"></i>
												</a>

											</li>

											<?php $onclick='';
												  if($type==1){
													$id=md5($appoinments_details['id']);
													  $onclick = 'onclick="remove_calldetails('.$id.')"';
												  }
											?>

											<li class="call-item">
												<a class="call-end" id="endcall" href="javascript:void(0);" <?php echo $onclick;?>>													
													<i class="material-icons">call_end</i>
												</a>
											</li>
										</ul>
									</div>
								</div>
								<!-- /Call Footer -->

							</div>
						</div>

					</div>
				</div>
			</div>
			<!-- /Call Wrapper -->


		</div>		
		<!-- /Page Content -->

		<!-- Footer -->

		<!-- /Footer -->

	</div>
	<!-- /Main Wrapper -->

	<script type="text/javascript">
		var base_url='<?php echo base_url();?>';
		var roles='<?php echo session('role');?>';
		// alert(roles);
	</script>

	<!-- jQuery -->
	<script src="<?php echo base_url();?>assets/js/jquery.min.js"></script>

	<!-- Bootstrap Core JS -->
	<script src="<?php echo base_url();?>assets/js/popper.min.js"></script>
	<script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>

	<script src="https://static.opentok.com/v2/js/opentok.min.js"></script>
	<script src="<?php echo base_url();?>assets/js/appoinments.js"></script>

	<script type="text/javascript">
		// replace these values with those generated in your TokBox Account
		$(document).ready(function () {
			var apiKey = '<?php echo !empty(settings("apiKey"))?(libsodiumDecrypt(settings("apiKey"))):"";?>';
			var sessionId = '<?php echo $appoinments_details['tokboxsessionId'];?>';
			var token = '<?php echo $appoinments_details['tokboxtoken'];?>';
			var appoinment_id='<?php echo md5($appoinments_details['id']);?>';
			let captions;
			let captionsRemovalTimer;
			let archive;
			getData('<?php echo base_url();?>'+'patient/appointment-captions/' + sessionId)
				.then(response => {
					console.log(response);
					captions = response;
					$('#startCaption').hide();
				})
				.catch(error => {
					handleError(error);
			});
			getData('<?php echo base_url();?>'+'patient/appointment-captions/archivestart_' + sessionId)
				.then(response => {
					console.log(response);
					archive = response;
					$('#startCaption').hide();
				})
				.catch(error => {
					handleError(error);
				});
			// Handling all of our errors here by alerting them
			function handleError(error) {
				if (error) {
					alert("The session disconnected. " + error);
					window.close();
				}
			}
			function getData(url) {
				return new Promise((resolve, reject) => {
					const xhr = new XMLHttpRequest();
					xhr.open('GET', url, true);
					xhr.onreadystatechange = function () {
						if (xhr.readyState === 4) {
							if (xhr.status === 200) {
								resolve(JSON.parse(xhr.responseText));
							} else {
								reject(xhr.statusText);
							}
						}
					};
					xhr.onerror = function () {
						reject(xhr.statusText);
					};
					xhr.send();
				});
			}

			function startCaptions(){
				console.log('start captions');
				getData('<?php echo base_url();?>'+'patient/appointment-captions/' + sessionId)
					.then(response => {
						console.log(response);
						captions = response;
					})
					.catch(error => {
						handleError(error);
					});
			}

			function stopCaptions(){
				console.log('stop captions');
				getData('<?php echo base_url();?>'+'patient/appointment-captions/stop_' + captions.captionsId)
					.then(response => {
						console.log(response);
						captions = response;
					})
					.catch(error => {
						handleError(error);
					});
			}

			var videoOn=false;
			var session = OT.initSession(apiKey, sessionId);
			$('#audio_enable').hide();

			$('#startCaption').click(function () {
				console.log('start Captions');
				$('#startCaption').hide();
				$('#startCaption').show();
				startCaptions();
			});
			$('#stopCaption').click(function () {
				$('#startCaption').show();
				$('#startCaption').hide();
				stopCaptions();
			});

			// Connect to the session
			session.connect(token, function(error) {
				// If the connection is successful, initialize a publisher and publish to the session
				if (error) {
					handleError(error);
				} else {

					// Subscribe to a newly created stream
					var subscriber=session.on('streamCreated', function(event) {
						session.subscribe(event.stream, 'subscriber', {
							insertMode: 'append',
							width: '100%',
							height: '100%'
						}, handleError);
					});

					subscriber.on("videoDisabled", function(event) {
					// You may want to hide the subscriber video element:
					domElement = document.getElementById(subscriber.id);
					domElement.style["visibility"] = "hidden";
					// You may want to add or adjust other UI.
					});

					subscriber.on('connectionDestroyed', function(event) {
						// Check if there are no more connections in the session
						getData('<?php echo base_url();?>'+'patient/appointment-captions/stoparchive_' + captions.captionsId)
							.then(response => {
								console.log(response);
								captions = response;
							})
							.catch(error => {
								handleError(error);
							});
					});

					try {
						subscriber.subscribeToCaptions(true);
					} catch (err) {
						console.warn(err);
					}
					subscriber.on('captionReceived', (event) => {
						const captionText = event.caption;
						const subscriberContainer = OT.subscribers.find().element;
						const [subscriberWidget] = subscriberContainer.getElementsByClassName('OT_widget-container');

						const oldCaptionBox = subscriberWidget.querySelector('.caption-box');
						if (oldCaptionBox) oldCaptionBox.remove();

						const captionBox = document.createElement('div');
						captionBox.classList.add('caption-box');
						captionBox.textContent = captionText;

						// remove the captions after 5 seconds
						const removalTimerDuration = 5 * 1000;
						clearTimeout(captionsRemovalTimer);
						captionsRemovalTimer = setTimeout(() => {
							captionBox.textContent = '';
						}, removalTimerDuration);

						subscriberWidget.appendChild(captionBox);
					});

					// Create a publisher
					var publisher = OT.initPublisher('publisher', {
						insertMode: 'append',
						width: '100%',
						height: '100%',
						publishAudio:true, 
					    publishVideo:false,
						publishCaptions: true
					}, handleError);


					$('#endcall').click(function () {  

						publisher.destroy();
						videoOn = true;

						end_call(appoinment_id);
						window.close();

					});

					//audio calling disable
					$('#audio_disable').click(function () { 

						publisher.publishAudio(false);

						$('#audio_disable').hide();
						$('#audio_enable').show();
					});
					//audio calling disable
					//audio calling enable
					$('#audio_enable').click(function () {  

						publisher.publishAudio(true);

						$('#audio_disable').show();
						$('#audio_enable').hide();
					});
					//audio calling enable

					session.publish(publisher, handleError);
				}

				session.on("sessionDisconnected", function(event) {
				    alert("The session disconnected. " + event.reason);
				    window.close();
				});

			});

		});

	</script>
	<?php
	$current_timezone = $appoinments_details['time_zone'];
	$old_timezone = session('time_zone');
	$todatetime = converToTz($appoinments_details["to_date_time"], $old_timezone, $current_timezone);
	?>
	<script type="text/javascript">
		
		var php_to_date = '<?php echo date("Y-m-d\TH:i:s",strtotime($todatetime)) ?>';
		var appoinment_id='<?php echo md5($appoinments_details['id']);?>';

		$(function(){

		    var calcNewYear = setInterval(function(){

		        date_future = Date.parse(php_to_date);
		        date_now = new Date();

		        seconds = Math.floor((date_future - (date_now))/1000);
		        minutes = Math.floor(seconds/60);
		        hours = Math.floor(minutes/60);
		        days = Math.floor(hours/24);
		        
		        hours = hours-(days*24);
		        minutes = minutes-(days*24*60)-(hours*60);
		        seconds = seconds-(days*24*60*60)-(hours*60*60)-(minutes*60);

		        if(seconds <= 0 && minutes <= 0){
		        	$("#time").text("Call Ended");
		        	end_call(appoinment_id);
		        	window.close();
			    }else{
			    	$("#time").text("Time Remaining : " + minutes + " minutes and " + seconds + " seconds");
			    }

		    },1000);
		});

	</script>

	<style type="text/css">
		#subscriber {
			position: absolute;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			z-index: 10;
		}

		#publisher {
			position: absolute;
			width: 360px;
			height: 240px;
			bottom: 10px;
			left: 10px;
			z-index: 100;
			border: 3px solid white;
			border-radius: 3px;
		}
	</style>


</body>
</html>