<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <div class="user-panel" style="height: 60px;">
            <div class="pull-left image">
                @if (auth()->user()->image ?? false)
                    <img src="uploads/users/thumbnail/{{ auth()->user()->image }}" class="img-responsive" alt="User Image">
                @else
                    <img src="images/no-image-available2.jpg" class="img-circle" alt="User Image">
                @endif
            </div>
            <div class="pull-left info">
                <p> {{ auth()->user()->email ?? 'Guest' }} </p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                <br><br>
            </div>
        </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <li>
            <a href="admin/dashboard">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-right pull-right"></i>
                </span>
            </a>
        </li>

        <li class="treeview {{ (Request::is('admin/customer') || Request::is('admin/edit_customer/*') || Request::is('admin/customer/*')) ? 'active' : '' }}">
            <a href="#">
                <i class="fa fa-users"></i> <span> ลูกค้า </span>
                <span class="label label-success">{{ \App\Member::where('role', 1)->count() ?? 0 }}</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="admin/customer-sync-zone"><i class="fa fa-circle-o"></i> ทำการ Sync Zone (App)</a></li>
                <li><a href="admin/customer?verify=false"><i class="fa fa-circle-o"></i> ลูกค้าที่ยังไม่ได้ Invite Code</a></li>
                <li><a href="admin/customer?verify=true"><i class="fa fa-circle-o"></i> ลูกค้าที่ Invite Code แล้ว</a></li>
                <li><a href="admin/customer"><i class="fa fa-circle-o"></i> รายชื่อลูกค้าทั้งหมด</a></li>
            </ul>
        </li>

        <li class="treeview {{ (Request::is('admin/promotion') || Request::is('admin/promotion/*')) ? 'active' : '' }}">
            <a href="#">
                <i class="fa fa-folder"></i> <span> โปรโมชั่น </span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="admin/promotion/create"><i class="fa fa-circle-o"></i> เพิ่มข้อมูล </a></li>
                <li><a href="admin/promotion"><i class="fa fa-circle-o"></i> รายการทั้งหมด </a></li>
            </ul>
        </li>

        <li class="treeview {{ (Request::is('admin/retainer_gallery') || Request::is('admin/retainer_gallery/*')) ? 'active' : '' }}">
            <a href="#">
                <i class="fa fa-folder"></i> <span> RetainerGalley </span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="admin/retainer_gallery/create"><i class="fa fa-circle-o"></i> เพิ่มข้อมูล </a></li>
                <li><a href="admin/retainer_gallery"><i class="fa fa-circle-o"></i> รายการทั้งหมด </a></li>
            </ul>
        </li>

        <li class="header">สินค้า</li>
        
        <li class="treeview {{ (Request::is('admin/category') || Request::is('admin/category/*')) ? 'active' : '' }}">
            <a href="#">
                <i class="fa fa-folder"></i> <span> หมวดสินค้า </span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="admin/category/create"><i class="fa fa-circle-o"></i> เพิ่มข้อมูล </a></li>
                <li><a href="admin/category"><i class="fa fa-circle-o"></i> รายการทั้งหมด </a></li>
            </ul>
        </li>

        <li class="treeview {{ (Request::is('admin/product') || Request::is('admin/product/*')) ? 'active' : '' }}">
            <a href="#">
                <i class="fa fa-product-hunt"></i> <span> สินค้า</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="admin/product/create"><i class="fa fa-circle-o"></i> เพิ่มข้อมูล </a></li>
                <li><a href="admin/product/import"><i class="fa fa-circle-o"></i> นำเข้าสินค้า </a></li>
                <li><a href="admin/product"><i class="fa fa-circle-o"></i> รายการทั้งหมด </a></li>
            </ul>
        </li>
        
        <li class="treeview {{ (Request::is('admin/order') || Request::is('admin/order/*')) ? 'active' : '' }}">
            <a href="#">
                <i class="fa fa-shopping-cart" aria-hidden="true"></i> <span> การสั่งซื้อ </span> 
                <span class="label label-success">{{ \App\Order::where('status', 0)->count() ?? 0 }}</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="admin/order?status=new"><i class="fa fa-circle-o"></i> การสั่งซื้อใหม่ </a></li>
                <li><a href="admin/order"><i class="fa fa-circle-o"></i> การสั่งซื้อทั้งหมด </a></li>
            </ul>
        </li>

        <li class="header">Voucher</li>

        <li class="treeview {{ (Request::is('admin/voucher_banner') || Request::is('admin/voucher_banner/*')) ? 'active' : '' }}">
            <a href="#">
                <i class="fa fa-picture-o"></i> <span> VoucherBanner </span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="admin/voucher_banner/create"><i class="fa fa-circle-o"></i> เพิ่มข้อมูล </a></li>
                <li><a href="admin/voucher_banner"><i class="fa fa-circle-o"></i> รายการทั้งหมด </a></li>
            </ul>
        </li>

        <li class="treeview {{ (Request::is('admin/voucher') || Request::is('admin/voucher/*')) ? 'active' : '' }}">
            <a href="#">
                <i class="fa fa-gift"></i> <span> Voucher </span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="admin/voucher/create"><i class="fa fa-circle-o"></i> เพิ่มข้อมูล </a></li>
                <li><a href="admin/voucher/import"><i class="fa fa-circle-o"></i> นำเข้า Voucher </a></li>
                <li><a href="admin/voucher"><i class="fa fa-circle-o"></i> รายการทั้งหมด </a></li>
            </ul>
        </li>

        <li class="treeview {{ (Request::is('admin/redemption') || Request::is('admin/redemption/*')) ? 'active' : '' }}">
            <a href="#">
                <i class="fa fa-gift"></i> <span> การแลกรับ Voucher </span>
                <span class="label label-danger">{{ \App\Redemption::where('approved', 0)->count() ?? 0 }}</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li>
                    <a href="admin/redemption?approved=0">
                        <i class="fa fa-circle-o"></i> การแลกรับที่รออนุมัติ 
                        <span class="label label-danger">{{ \App\Redemption::where('approved', 0)->count() ?? 0 }}</span>
                    </a>
                </li>
                <li><a href="admin/redemption"><i class="fa fa-circle-o"></i> ประวัติการแลกรับทั้งหมด </a></li>
            </ul>
        </li>

        <li class="header">Order Pickups</li>

        <li class="treeview {{ (Request::is('admin/order_pickup') || Request::is('admin/order_pickup/*')) ? 'active' : '' }}">
            <a href="#">
                <i class="fa fa-map-pin"></i> <span> Pickup </span>
                <span class="label label-danger"> {{ \App\OrderPickup::where('checked', 0)->count() }} </span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="admin/order_pickup"><i class="fa fa-circle-o"></i> Order Pickups </a></li>
            </ul>
        </li>

        <li class="header">Balance Due</li>

        <li class="treeview {{ (Request::is('admin/payment') || Request::is('admin/payment/*')) ? 'active' : '' }}">
            <a href="#">
                <i class="fa fa-credit-card-alt"></i> <span> Payments </span>
                <span class="label label-danger"> {{ \App\Payment::where('confirmed', 0)->where('full_step', 1)->count() }} </span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="admin/payment"><i class="fa fa-circle-o"></i> Balance Due Payments </a></li>
            </ul>
        </li>

        <li class="treeview {{ (Request::is('admin/comment') || Request::is('admin/report') || Request::is('admin/report/*') || Request::is('admin/checking') || Request::is('admin/checking/*')) ? 'active' : '' }}">
            <a href="#">
                <i class="fa fa-file"></i> <span> รายงาน </span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="admin/checking"><i class="fa fa-circle-o"></i> รายงานการเช็๋คอิน </a></li>
                <li><a href="admin/comment"><i class="fa fa-circle-o"></i> รายงาน feedback eorder </a></li>
            </ul>
        </li>

        <li class="header">ข้อมูลพื้นฐาน</li>

        <li class="treeview {{ (Request::is('admin/bank') || Request::is('admin/bank/*')) ? 'active' : '' }}">
            <a href="#">
                <i class="fa fa-money"></i> <span> บัญชีธนาคารรับโอน </span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="admin/bank/create"><i class="fa fa-circle-o"></i> เพิ่มบัญชีธนาคารรับโอน </a></li>
                <li><a href="admin/bank"><i class="fa fa-circle-o"></i> บัญชีธนาคารรับโอนทั้งหมด </a></li>
            </ul>
        </li>

        <li class="treeview {{ (Request::is('admin/zone') || Request::is('admin/zone/*')) ? 'active' : '' }}">
            <a href="#">
                <i class="fa fa-globe"></i> <span> โซน </span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="admin/zone/create"><i class="fa fa-circle-o"></i> เพิ่มโซน </a></li>
                <li><a href="admin/zone"><i class="fa fa-circle-o"></i> โซนทั้งหมด </a></li>
            </ul>
        </li>

        <li class="treeview {{ (Request::is('admin/member') || Request::is('admin/member/*')) ? 'active' : '' }}">
            <a href="#">
                <i class="fa fa-users"></i> <span> ทีมงาน </span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="admin/member/create"><i class="fa fa-circle-o"></i> เพิ่ม account</a></li>
                <li><a href="admin/member"><i class="fa fa-circle-o"></i> ทีมงานทั้งหมด</a></li>
            </ul>
        </li>

        <li class="treeview {{ (Request::is('admin/setting') || Request::is('admin/setting/*')) ? 'active' : '' }}">
            <a href="#">
                <i class="fa fa-gear" aria-hidden="true"></i> <span> ตั้งค่าข้อมูลทั่วไป</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="admin/setting/1?page=contact"><i class="fa fa-circle-o"></i> Contact </a></li>
                <li><a href="admin/setting/1?page=terms"><i class="fa fa-circle-o"></i> Terms of Service </a></li>
                <li><a href="admin/setting/1?page=price"><i class="fa fa-circle-o"></i> เอกสาร Price List </a></li>
                <li><a href="admin/setting/1?page=retainer_gallery"><i class="fa fa-circle-o"></i> เอกสาร RetainerGalley </a></li>
                <li><a href="admin/setting/1?page=cover"><i class="fa fa-circle-o"></i> รูป Pop-up หน้าหลักแอป  </a></li>
                <li><a href="admin/setting/1?page=qrcode"><i class="fa fa-circle-o"></i> รูป qrcode พร้อมเพล </a></li>
            </ul>
        </li>

        <li class="treeview {{ (Request::is('admin/user/*') ? 'active' : '') }}">
            <a href="#">
                <i class="fa fa-gear"></i> <span> ตั้งค่า </span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="admin/user/profile"><i class="fa fa-circle-o"></i> โปรไฟล์</a></li>
                <li><a href="admin/user/change_password"><i class="fa fa-circle-o"></i> เปลี่ยนรหัสผ่าน</a></li>
            </ul>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
</aside>