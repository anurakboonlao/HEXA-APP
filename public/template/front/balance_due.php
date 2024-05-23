<!DOCTYPE html>
<html lang="en">
<head>
	<title></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<?php 
		include"include/function.php";
		css_function();
	?>
	
</head>
<body>
  
<?php include("include/navbar.php"); ?>  
<div class="pd-denti1">

<div class="container-fluid pd-denti3 mg-rewa1">
	<div class="row">
		<div class="col-lg-12 pd-denti2">
			<div class="bg-rewa3">
				<div class="font-denti1 dis-rewa pd-balance1">
					<h2>ใบวางบิล</h2>
				</div>

				
				<!-- Nav tabs -->
				<ul class="nav nav-tabs" role="tablist">
					<li class="nav-item">
					  <a class="nav-link active" data-toggle="tab" href="#process">รายการชำระ</a>
					</li>
					<li class="nav-item">
					  <a class="nav-link" data-toggle="tab" href="#complete">ประวัติการชำระเงิน</a>
					</li>
				</ul>
				<!-- Tab panes -->
				<div class="tab-content">
					<div id="process" class="tab-pane pd-den-tab1 active">
						<div class="row">
							<div class="col-xl-5 ml-xl-auto col-md-6">
								<div class="bg-rewa4 font-rewa1">
									<font class="dis-rewa1">ยอดชำระ</font><b>00,000.00</b><span>บาท</span>
									
								</div>
							</div>
						</div>
						
					</div>
					<div id="complete" class="tab-pane pd-den-tab1 fade">
						aaaaaaaa
					</div>
				</div>
				
			</div>
		</div>
	</div>
</div>	
	
<!-- Modal reward-->
<div class="modal" id="modal-reward1" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
		<div class="font-denti1">
			<h3>Starbuck Card</h3>
		</div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pd-rewa6">
      	<div class="row">
		  	<div class="col-md-6">
				<div class="line-rewa4 pd-rewa7">
					<img src="images/reward/img-reward3.png" width="100%">
					<p>ทุก 25 บาท = 1 คะแนน<br>ทุก 1,000 คะแนน = 100 บาท</p>
				</div>
			</div>
		  	<div class="col-md-6 font-rewa2">
				<p class="point6">ข้อความ + เงื่อนไข<br>xxxxxxxxxxxxxxxxxxxxx<br>xxxxxxxxxxxxxxxxxxxxx</p>
				<span>ระบุมูลค่าของรางวัลและคะแนนที่ต้องการแลก</span>
				<form>
					<label>มูลค่าของรางวัล</label>
					<input type="number" id="" placeholder="" class="form-rewa1">
					<label>คะแนนที่ใช้แลก</label>
					<input type="number" id="" placeholder="" class="form-rewa1">
				</form>
				<div class="mg-rewa2">
					<button class="btn-rewa4">ใส่ตะกร้าของรางวัล</button>
					<button class="btn-rewa4 btn-rewa6" type="button" data-toggle="modal" data-target="#modal-exchange1">แลกคะแนน</button>
				</div>
			</div>
			
		</div>
      </div>
    </div>
  </div>
</div>
	
<!-- Modal Exchange-->
<div class="modal" id="modal-exchange1" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
		<div class="font-denti1">
			<h3>แลกของรางวัล</h3>
		</div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pd-excha1">
      	<div class="row">
		  	<div class="col-md-6">
				<div class="bg-excha1">
					<p><b>นพ.นพดล วรรณสิน</b><br>
						โรงพยาบาลเชียงรายประชานุเคราะห์ (เชียงราย)<br>ฝ่ายทันตกรรม เลขที่ 1039 ถ.สถานพยาบาล<br>ต.รอบเวียง อ.เมือง จ.เชียงราย 57000
					</p>
				</div>
				<div class="bg-excha1">
					<p><b>นพ.นพดล วรรณสิน</b><br>
						โรงพยาบาลเชียงรายประชานุเคราะห์ (เชียงราย)<br>ฝ่ายทันตกรรม เลขที่ 1039 ถ.สถานพยาบาล<br>ต.รอบเวียง อ.เมือง จ.เชียงราย 57000
					</p>
				</div>
				<div class="bg-excha1">
					<p><b>นพ.นพดล วรรณสิน</b><br>
						โรงพยาบาลเชียงรายประชานุเคราะห์ (เชียงราย)<br>ฝ่ายทันตกรรม เลขที่ 1039 ถ.สถานพยาบาล<br>ต.รอบเวียง อ.เมือง จ.เชียงราย 57000
					</p>
				</div>
				<button class="btn-excha1" type="button" data-toggle="modal" data-target="#modal-address1"><span class="icon-plus input-group-addon pd-excha2"></span>เพิ่มที่อยู่จัดส่ง</button>
			</div>
		  	<div class="col-md-6">
				<ul class="line-excha1">
					<li><input type="checkbox" checked="checked" id="awardExchange1"><label for="awardExchange1" class="font-excha2">เลือกทั้งหมด</label></li>
					<li class="pd-bas2"><input type="checkbox" id="awardExchange2">
						<label for="awardExchange2">
							<div class="left-re-poin1">
								<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="currentColor" class="bi bi-trash ri-excha1" viewBox="0 0 16 16">
								  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
								  <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
								</svg><p><span class="pl-excha1">200 บาท</span><span class="wid-excha1 point1">Star Buck Card</span></p>
							</div>
						</label>
					</li>
					<li class="pd-bas2"><input type="checkbox" id="awardExchange3">
						<label for="awardExchange3">
							<div class="left-re-poin1">
								<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="currentColor" class="bi bi-trash ri-excha1" viewBox="0 0 16 16">
								  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
								  <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
								</svg><p><span class="pl-excha1">200 บาท</span><span class="wid-excha1 point1">Star Buck Card</span></p>
							</div>
						</label>
					</li>
					<li class="pd-bas2"><input type="checkbox" id="awardExchange4">
						<label for="awardExchange4">
							<div class="left-re-poin1">
								<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="currentColor" class="bi bi-trash ri-excha1" viewBox="0 0 16 16">
								  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
								  <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
								</svg><p><span class="pl-excha1">200 บาท</span><span class="wid-excha1 point1">Star Buck Card</span></p>
							</div>
						</label>
					</li>
				</ul>
				
				<form class="font-excha1 mg-excha1">
					<label>มูลค่าของรางวัล (บาท)</label>
					<input type="number" id="" placeholder="" class="form-rewa1">
					<label>คะแนนที่ใช้แลก (คะแนน)</label>
					<input type="number" id="" placeholder="" class="form-rewa1">
				</form>
				<div class="mg-rewa2">
					<button class="btn-rewa4 btn-rewa6 float-right" type="button" data-toggle="modal" data-target="#modal-success1">แลกคะแนน</button>
				</div>
			</div>
			
		</div>
      </div>
    </div>
  </div>
</div>
	
<!-- Modal address-->
<div class="modal" id="modal-address1" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
		<div class="font-denti1">
			<h3>แลกของรางวัล</h3>
		</div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pd-excha1">
      	<div class="row">
		  	<div class="col-md-6 font-excha1 font-add1">
				<h4>เพิ่มที่อยู่จัดส่ง</h4>
				<form>
					<label>ชื่อ-สกุล</label>
					<input type="text" id="" placeholder="กรุณาใส่ชื่อ-สกุล" class="form-add1">
					<label>ที่อยู่</label>
					<input type="text" id="" placeholder="กรุณาใส่ที่อยู่" class="form-add1">
					
					<div class="row">
						<div class="col-lg-6">
							<label>จังหวัด</label>
							<div class="wrap-drop" id="province">
								<span>เลือกจังหวัด</span>
								<ul class="drop">
									<li class="selected"><a>เชียงราย</a></li>
									<li><a>เชียงใหม่</a></li>
									<li><a>ลำปาง</a></li>
								</ul>
							</div>
						</div>
						<div class="col-lg-6">
							<label>อำเภอ</label>
							<div class="wrap-drop" id="district">
								<span>เลือกอำเภอ</span>
								<ul class="drop">
									<li class="selected"><a>แม่อาย</a></li>
									<li><a>ฝาง</a></li>
									<li><a>ไชยปราการ</a></li>
								</ul>
							</div>
						</div>
						<div class="col-lg-6">
							<label>รหัสไปรษณีย์</label>
							<input type="text" id="" placeholder="กรุณาใส่รหัสไปรษณีย์" class="form-add1">
						</div>
						<div class="col-lg-6">
							<label>หมายเลขโทรศัพท์</label>
							<input type="text" id="" placeholder="กรุณาใส่เบอร์โทรศัพท์" class="form-add1">
						</div>
					</div>
					
					<label>อีเมล</label>
					<input type="email" id="" placeholder="กรุณาใส่อีเมล" class="form-add1">
					
					<ul class="font-add2">
						<li><input type="checkbox" checked="checked" id="checkbox1"><label for="checkbox1">ตั้งเป็นที่อยู่เริ่มต้น</label></li>
					</ul>
					
					<button class="btn-rewa4 ct-add1" type="button" data-toggle="modal" data-target="#modal-exchange1">บันทึก</button>
					
				</form>
			</div>
		  	<div class="col-md-6">
				<ul class="line-excha1 hei-add1">
					<li><input type="checkbox" checked="checked" id="awardExcha1"><label for="awardExcha1" class="font-excha2">เลือกทั้งหมด</label></li>
					<li class="pd-bas2"><input type="checkbox" id="awardExcha2">
						<label for="awardExcha2">
							<div class="left-re-poin1">
								<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="currentColor" class="bi bi-trash ri-excha1" viewBox="0 0 16 16">
								  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
								  <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
								</svg><p><span class="pl-excha1">200 บาท</span><span class="wid-excha1 point1">Star Buck Card</span></p>
							</div>
						</label>
					</li>
					<li class="pd-bas2"><input type="checkbox" id="awardExcha3">
						<label for="awardExcha3">
							<div class="left-re-poin1">
								<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="currentColor" class="bi bi-trash ri-excha1" viewBox="0 0 16 16">
								  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
								  <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
								</svg><p><span class="pl-excha1">100 บาท</span><span class="wid-excha1 point1">Central Card</span></p>
							</div>
						</label>
					</li>
					<li class="pd-bas2"><input type="checkbox" id="awardExcha4">
						<label for="awardExcha4">
							<div class="left-re-poin1">
								<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="currentColor" class="bi bi-trash ri-excha1" viewBox="0 0 16 16">
								  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
								  <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
								</svg><p><span class="pl-excha1">200 บาท</span><span class="wid-excha1 point1">Star Buck Card</span></p>
							</div>
						</label>
					</li>
				</ul>
				
				<form class="font-excha1 mg-excha1">
					<label>มูลค่าของรางวัล (บาท)</label>
					<input type="number" id="" placeholder="" class="form-rewa1">
					<label>คะแนนที่ใช้แลก (คะแนน)</label>
					<input type="number" id="" placeholder="" class="form-rewa1">
				</form>
				<div class="mg-rewa2">
					<button class="btn-rewa4 btn-rewa6 float-right" type="button" data-toggle="modal" data-target="#modal-success1">แลกคะแนน</button>
				</div>
			</div>
			
		</div>
      </div>
    </div>
  </div>
</div>
	
<!-- Modal successfully-->
<div class="modal" id="modal-success1" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-suc1" role="document">
    <div class="modal-content">
      <div class="modal-header">
		<div class="font-suc1">
			<h3>แลกคะแนนสำเร็จ</h3>
		</div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pd-excha1 font-suc2">
		  <div class="check-suc1"><span class="icon-check input-group-addon"></span></div>
		  <p>ของรางวัลจะถูกจัดส่งภายใน 7 วัน</p>
		  <button class="btn-rewa4 btn-suc1">Submit</button>
      </div>
    </div>
  </div>
</div>
	
	

</div>
<?php script_function(); ?>	
</body>
</html>