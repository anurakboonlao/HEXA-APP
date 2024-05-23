<div class="row" id="form-add-list">
    <div class="col-md-5 col-sm-5 col-xs-12">
        <div class="form-group">
            <label for="">อะไหล่*</label>
            <div class="input-group">
                <div class="input-group-addon">
                    :
                </div>
                {{ Form::select('product_id', $products, '', ['class' => 'form-control select2']) }} 
            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-2 col-xs-12">
        <div class="form-group">
            <label for="">ราคาซื้อ (ตัวอักษร) *</label>
            <div class="input-group">
                <div class="input-group-addon">
                    :
                </div>
                {{ Form::text('price_code', '', ['class' => 'form-control']) }} 
            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-2 col-xs-12">
        <div class="form-group">
            <label for="">จำนวน*</label>
            <div class="input-group">
                <div class="input-group-addon">
                    :
                </div>
                {{ Form::number('qty', '', ['class' => 'form-control']) }} 
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
            <label for="">หมายเหตุ</label>
            <div class="input-group">
                <div class="input-group-addon">
                    :
                </div>
                {{ Form::text('note', '', ['class' => 'form-control']) }} 
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 text-right">
        <div class="form-group">
            <button class="btn btn-primary">
                <i class="fa fa-plus"></i>
            </button>
        </div>
    </div>
</div>