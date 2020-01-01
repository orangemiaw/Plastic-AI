<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="row">
	<div class="col-md-12 col-sm-12">
		<div class="card bd-0 shadow-base pd-15">
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
                            <h3>Authenticate</h3>
							<b>Request URL</b><br>
							<code><?php print base_url('api/v1/authenticate');?></code><br><br>

							<b>Request Method</b><br>
							<code>POST</code><br><br>
							
							<p><b>Request Parameter</b></p>
							<div class="bd bd-gray-300 rounded table-responsive">
								<table class="table">
									<thead>
										<tr>
											<th class="min-w text-center align-middle">Required</th>
											<th class="align-middle">Name</th>
											<th class="align-middle">Type</th>
											<th class="align-middle">Description</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="min-w text-center ">
												<b>YES</b>
											</td>
											<td class="align-middle">
												<code>login</code>
											</td>
											<td class="align-middle">
												String
											</td>
											<td class="align-middle">
												Your username to login this site
											</td>
										</tr>
										<tr>
											<td class="min-w text-center ">
												<b>YES</b>
											</td>
											<td class="align-middle">
												<code>password</code>
											</td>
											<td class="align-middle">
												String
											</td>
											<td class="align-middle">
												Your password to login this site
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<br>
							
							<p><b>Sample Response</b></p>
							<pre class="bd bd-gray-300 rounded"><code>
 {
   "data": {
     "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.IntcImxvZ2luXCI6XCJpbWXXX"
   },
   "meta": {
     "code": 200,
     "message": [
       "OK"
     ]
   }
 }
							</pre></code>

                            <hr>
                            <h3>Detect Plastic</h3>
							<b>Request URL</b><br>
							<code><?php print base_url('api/v1/detect_plastic');?></code><br><br>

							<b>Request Method</b><br>
							<code>POST</code><br><br>
							
							<p><b>Request Header</b></p>
							<div class="bd bd-gray-300 rounded table-responsive">
								<table class="table">
									<thead>
										<tr>
											<th class="min-w text-center align-middle">Required</th>
											<th class="align-middle">Name</th>
											<th class="align-middle">Type</th>
											<th class="align-middle">Description</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="min-w text-center ">
												<b>YES</b>
											</td>
											<td class="align-middle">
												<code>Authorization</code>
											</td>
											<td class="align-middle">
												String
											</td>
											<td class="align-middle">
												Access code that has been gotten from <code>authenticate</code>
											</td>
											<td class="align-middle">
												<code>Authorization: Bearer {access_token}</code>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<br>
							
							<p><b>Request Parameter</b></p>
							<div class="bd bd-gray-300 rounded table-responsive">
								<table class="table">
									<thead>
										<tr>
											<th class="min-w text-center align-middle">Required</th>
											<th class="align-middle">Name</th>
											<th class="align-middle">Type</th>
											<th class="align-middle">Description</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td rowspan="3" class="min-w text-center">
												<b>YES (choose any of three)</b>
											</td>
											<td class="align-middle">
												<code>image_url</code>
											</td>
											<td class="align-middle">
												String
											</td>
											<td class="align-middle">
												URL of the image.<br>
												Note: getting images from URLs may take a long time due to internet connection problems, so it is recommended that you upload images directly by using image_file.
											</td>
										</tr>
										<tr>
											<td class="align-middle">
												<code>image_base64</code>
											</td>
											<td class="align-middle">
												String
											</td>
											<td class="align-middle">
												Base64 encoded binary data of the image.<br>
												These three parameters (image_url, image_file and image_base64) will be adopted in the following order of precedence:<br>
												Highest: image_file; Lowest: image_url.
											</td>
										</tr>
										<tr>
											<td class="align-middle">
												<code>image_file</code>
											</td>
											<td class="align-middle">
												File
											</td>
											<td class="align-middle">
												The binary data of the image, uploaded via POST multipart/form-data.
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<br>
							
							<p><b>Sample Response</b></p>
							<pre class="bd bd-gray-300 rounded"><code>
 {
   "data": {
     "request_id": "71ff8880-ab84-489e-8e64-ae6d1414d869",
     "type": "plastic",
     "percentage": 46.52628004550933837890625,
     "file": "<?php print base_url('files/images/detected/71ff8880-ab84-489e-8e64-ae6d1414d869/052822f8-e796-4c3c-85df-5d3254c72b13.jpg');?>",
     "time_used": 0.690277099609375,
     "status": 1
   },
   "meta": {
     "code": 200,
     "message": [
       "OK"
     ]
   }
 }
							</pre></code>

                            <hr>
                            <h3>Detection Logs</h3><br>
							
							<p><b>Request Header</b></p>
							<div class="bd bd-gray-300 rounded table-responsive">
								<table class="table">
									<thead>
										<tr>
											<th class="min-w text-center align-middle">Required</th>
											<th class="align-middle">Name</th>
											<th class="align-middle">Type</th>
											<th class="align-middle">Description</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="min-w text-center ">
												<b>YES</b>
											</td>
											<td class="align-middle">
												<code>Authorization</code>
											</td>
											<td class="align-middle">
												String
											</td>
											<td class="align-middle">
												Access code that has been gotten from <code>authenticate</code>
											</td>
											<td class="align-middle">
												<code>Authorization: Bearer {access_token}</code>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<br>

                            <h5>List Detection Logs</h5>
							<b>Request URL</b><br>
							<code><?php print base_url('api/v1/detection_logs');?></code><br><br>

							<b>Request Method</b><br>
							<code>GET</code><br><br>
							
							<p><b>Sample Response</b></p>
							<pre class="bd bd-gray-300 rounded"><code>
 {
   "data": [
     {
       "request_id": "ed211bfb-ea1c-4446-8887-dc1f82ebc1c1",
       "type": "PET",
       "percentage": "79.578906297684",
       "file": "<?php print base_url('files/images/detected/ed211bfb-ea1c-4446-8887-dc1f82ebc1c1/c894bdc2-40ce-4063-8879-d9f96bd15f2d.jpg');?>",
       "time_used": "0.72007584571838",
       "status": 1
     },
     {
       "request_id": "1b095ab9-ad48-437b-8b18-bf3d50ec1cb5",
       "type": "PVC",
       "percentage": "79.578906297684",
       "file": "<?php print base_url('files/images/detected/1b095ab9-ad48-437b-8b18-bf3d50ec1cb5/7404eda5-f34c-4031-bda5-8503835191af.jpg');?>",
       "time_used": "0.54953050613403",
       "status": 1
     },
     {
       "request_id": "e009bda3-7f2f-4db3-a581-ccbbcc02a5fa",
       "type": "PET",
       "percentage": "29.092353582382",
       "file": "<?php print base_url('files/images/detected/e009bda3-7f2f-4db3-a581-ccbbcc02a5fa/2c5495b2-09fb-44fe-8deb-59c20d737d37.jpg');?>",
       "time_used": "1.0406453609467",
       "status": 1
     },
     {
       "request_id": "71ff8880-ab84-489e-8e64-ae6d1414d869",
       "type": "PET",
       "percentage": "46.526280045509",
       "file": "<?php print base_url('files/images/detected/71ff8880-ab84-489e-8e64-ae6d1414d869/052822f8-e796-4c3c-85df-5d3254c72b13.jpg');?>",
       "time_used": "0.69027709960938",
       "status": 1
     }
   ],
   "meta": {
     "code": 200,
     "message": [
       "OK"
     ],
     "pagination": {
       "current_page": 1,
       "total_data": 4,
       "item_per_page": 20,
       "links": [
         "<?php print base_url('api/v1/detection_logs');?>"
       ]
     }
   }
 }
							</pre></code>

                            <h5>Detail Detection Logs</h5>
							<b>Request URL</b><br>
							<code><?php print base_url('api/v1/detection_logs/detail/:id');?></code><br><br>

							<b>Request Method</b><br>
							<code>GET</code><br><br>
							
							<p><b>Sample Response</b></p>
							<pre class="bd bd-gray-300 rounded"><code>
 {
   "data": {
     "request_id": "ed211bfb-ea1c-4446-8887-dc1f82ebc1c1",
     "type": "PET",
     "percentage": "79.578906297684",
     "file": "<?php print base_url('files/images/detected/ed211bfb-ea1c-4446-8887-dc1f82ebc1c1/c894bdc2-40ce-4063-8879-d9f96bd15f2d.jpg');?>",
     "time_used": "0.72007584571838",
     "brain_response": {
       "_id": "ed211bfb-ea1c-4446-8887-dc1f82ebc1c1",
       "type": "cardboard",
       "percentage": 79.5789062976837158203125,
       "binarizer": [
         {
           "shape": [
             1,
             3072
           ],
           "data": [
             [
               0.1647058823529411741848349493011482991278171539306640625,
               0.2117647058823529382376449348157620988786220550537109375,
               0.321568627450980415449777183312107808887958526611328125,
               0.1647058823529411741848349493011482991278171539306640625,
              // -- cutted -- //
               0.1294117647058823650230152679796447046101093292236328125,
               0.1490196078431372528338982874629436992108821868896484375,
               0.0549019607843137247282783164337160997092723846435546875,
               0.0862745098039215674301516401101252995431423187255859375,
               0.15294117647058824704942026073695160448551177978515625
             ]
           ]
         }
       ],
       "file": "<?php print base_url('files/images/detected/ed211bfb-ea1c-4446-8887-dc1f82ebc1c1/c894bdc2-40ce-4063-8879-d9f96bd15f2d.jpg');?>",
       "time_used": 0.7200758457183837890625
     },
     "status": 1
   },
   "meta": {
     "code": 200,
     "message": [
       "OK"
     ]
   }
 }
							</pre></code>

                            <h5>Delete Detection Logs</h5>
							<b>Request URL</b><br>
							<code><?php print base_url('api/v1/detection_logs/delete/:id');?></code><br><br>

							<b>Request Method</b><br>
							<code>GET</code><br><br>
							
							<p><b>Sample Response</b></p>
							<pre class="bd bd-gray-300 rounded"><code>
 {
   "data": {
     "request_id": "ed211bfb-ea1c-4446-8887-dc1f82ebc1c1",
     "deleted": false
   },
   "meta": {
     "code": 200,
     "message": [
       "OK"
     ]
   }
 }
							</pre></code>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>