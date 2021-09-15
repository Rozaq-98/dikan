<!-- jQuery 3 -->
<script src="{{ asset('AdminLTE/bower_components/jquery/dist/jquery-3.3.1.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('AdminLTE/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('AdminLTE/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- datepicker -->
<script src="{{ asset('AdminLTE/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{ asset('AdminLTE/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
<!-- Slimscroll -->
<script src="{{ asset('AdminLTE/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('AdminLTE/bower_components/fastclick/lib/fastclick.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('AdminLTE/dist/js/adminlte.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('AdminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<!-- bootstrap time picker -->
<script src="{{ asset('AdminLTE/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>

<script>

     function onChangeProduct(obj){
        // console.log(obj);
        var idproduct = obj.value;
        // console.log(assetcabang);
        $.ajax({
            method  : "POST",
            url     : "{{url('/api/assethis')}}",
            data    :{
                "_token"  : "{{ csrf_token() }}",
                "idasset" : idasset
            },

            success:function(response) {
                console.log(response);

                /*reset select loc_kota*/
                $('#idlokasiawal').val('');
                $('#idcabangawal').val('');
                // $('#kondisiawal').val('');
                // append - default value
                // $('#cabangtujuan').append('<option value="">Choose Cabang</option>');
                // append - each response
                $.each(response, function(index, obj){
                    for (let i = 0; i < obj.length; i++) {
                        $('#idlokasiawal').val(obj[i].id_lokasi_akhir);
                        $('#idcabangawal').val(obj[i].id_cabang_akhir);
                        // $('#cabangtujuan').append('<option value="'+ obj[i].id +'">'+ obj[i].cabang_name +'</option>');
                    }
                })
            },
            error:function(error){
                console.log(error);
            }
        });
    }

    function onChangeProv(obj){
        var idProv = obj.value;
        $.ajax({
            method 	: "POST",
            url 	: "{{url('/api/location/kota')}}",
            data 	:{
                "_token"  : "{{ csrf_token() }}",
                "id_prov" : idProv
            },
            success:function(response) {
                // console.log(response);

                /*reset select loc_kota*/
                $('#loc_kota').empty();

                // append - default value
                $('#loc_kota').append('<option value="">Choose Lokasi Kota</option>');
                // append - each response
                $.each(response, function(index, obj){
                    for (let i = 0; i < obj.length; i++) {
                        $('#loc_kota').append('<option value="'+ obj[i].id +'">'+ obj[i].name +'</option>');
                    }
                })
            },
            error:function(error){
                console.log(error);
            }
        });
    }

    function onChangesubTipe(obj){
        // console.log(obj);
        var tipe = obj.value;
        // console.log(tipe);
        $.ajax({
            method  : "POST",
            url     : "{{url('/api/subtipe')}}",
            data    :{
                "_token"  : "{{ csrf_token() }}",
                "id_tipe_asset" : tipe
            },

            success:function(response) {
                // console.log(response);

                /*reset select loc_kota*/
                $('#subtipe').empty();

                // append - default value
                $('#subtipe').append('<option value="">Choose Sub Tipe</option>');
                // append - each response
                $.each(response, function(index, obj){
                    for (let i = 0; i < obj.length; i++) {
                        $('#subtipe').append('<option value="'+ obj[i].id +'">'+ obj[i].subtipe_asset_name +'</option>');
                    }
                })
            },
            error:function(error){
                console.log(error);
            }
        });
    }

    function onChangeAsset(obj){
        // console.log(obj);
        var subtipe = obj.value;
        var cabangawal =  $('#idcabang').val();
        var category = $('#idcatasset').val();
        // console.log(tipe);
        $.ajax({
            method  : "POST",
            url     : "{{url('/api/asset')}}",
            data    :{
                "_token"  : "{{ csrf_token() }}",
                "subtipe" : subtipe, 
                "cabangawal" : cabangawal, 
                "category" : category 
            },

            success:function(response) {
                // console.log(response);

                /*reset select loc_kota*/
                $('#noidasset').empty();

                // append - default value
                $('#noidasset').append('<option value="">Choose Asset</option>');
                // append - each response
                $.each(response, function(index, obj){
                    for (let i = 0; i < obj.length; i++) {
                        $('#noidasset').append('<option value="'+ obj[i].id +'">'+ obj[i].merk +' - '+ obj[i].no_id_asset +'</option>');
                    }
                })
            },
            error:function(error){
                console.log(error);
            }
        });
    }

     function onChangeLastQtyTeam(obj){
        // console.log(obj);
        var asset = obj.value; 
        var idcabang = $('#idcabang').val();   
        // console.log(asset);
        $.ajax({
            method  : "POST",
            url     : "{{url('/api/assetqty')}}",
            data    :{
                "_token"  : "{{ csrf_token() }}",
                "asset" : asset,
                "idcabang" : idcabang
              
            },

            success:function(response) {
                // console.log(response);

                /*reset select loc_kota*/
                $('#stock').empty();
                $('#stock').val('0');
                $('#kebutuhan').empty();
                $('#kebutuhan').val('0');
                 // $('#team').val('Choose Team');

                // append - default value
                // $('#stock').append('<option value="">Choose Last QTY</option>');
                // append - each response
               $.each(response, function(index, obj){
                    for (let i = 0; i < obj.length; i++) {
                        // $('#idkondisiawal').val(obj[i].id_kondisi_akhir);
                        $('#stock').val(obj[i].last_qty);
                        $('#kebutuhan').val(obj[i].kebutuhan*obj[i].last_qty);
                        // console.log($('#kebutuhan').val(obj[i].kebutuhan))
                        // $('#cabangtujuan').append('<option value="'+ obj[i].id +'">'+ obj[i].cabang_name +'</option>');
                    }
                })
            },
            error:function(error){
                console.log(error);
            }
        });
    }

    function onChangeLastQtyJabatan(obj){
        // console.log(obj);
        var jabatanqty = obj.value;       
        // console.log(jabatanqty);
        $.ajax({
            method  : "POST",
            url     : "{{url('/api/jabatanqty')}}",
            data    :{
                "_token"  : "{{ csrf_token() }}",
                "jabatanqty" : jabatanqty
              
            },

            success:function(response) {
                console.log(response);

                /*reset select loc_kota*/
                $('#stockjabatan').empty();
                $('#stockjabatan').val('0');
                // append - default value
                // $('#stock').append('<option value="">Choose Last QTY</option>');
                // append - each response

               $.each(response, function(index, obj){
                    for (let i = 0; i < obj.length; i++) {
                        // $('#idkondisiawal').val(obj[i].id_kondisi_akhir);
                        $('#stockjabatan').val(obj[i].last_qty);
                        // $('#cabangtujuan').append('<option value="'+ obj[i].id +'">'+ obj[i].cabang_name +'</option>');
                    }
                })
            },
            error:function(error){
                console.log(error);
            }
        });
    }
    
    function onChangeAssetLokasi(obj){
        // console.log(obj);
        var idasset = obj.value;
        // console.log(assetcabang);
        $.ajax({
            method  : "POST",
            url     : "{{url('/api/assethis')}}",
            data    :{
                "_token"  : "{{ csrf_token() }}",
                "idasset" : idasset
            },

            success:function(response) {
                console.log(response);

                /*reset select loc_kota*/
                $('#idlokasiawal').val('');
                $('#idcabangawal').val('');
                // $('#kondisiawal').val('');
                // append - default value
                // $('#cabangtujuan').append('<option value="">Choose Cabang</option>');
                // append - each response
                $.each(response, function(index, obj){
                    for (let i = 0; i < obj.length; i++) {
                        $('#idlokasiawal').val(obj[i].id_lokasi_akhir);
                        $('#idcabangawal').val(obj[i].id_cabang_akhir);
                        // $('#cabangtujuan').append('<option value="'+ obj[i].id +'">'+ obj[i].cabang_name +'</option>');
                    }
                })
            },
            error:function(error){
                console.log(error);
            }
        });
    }
    function onChangeAssetCabang(obj){
        // console.log(obj);
        var assetcabang = obj.value;
        // console.log(assetcabang);
        $.ajax({
            method  : "POST",
            url     : "{{url('/api/assetcabang')}}",
            data    :{
                "_token"  : "{{ csrf_token() }}",
                "assetcabang" : assetcabang
            },

            success:function(response) {
                // console.log(response);

                /*reset select loc_kota*/
                $('#cabangtujuan').empty();

                // append - default value
                $('#cabangtujuan').append('<option value="">Choose Cabang</option>');
                // append - each response
                $.each(response, function(index, obj){
                    for (let i = 0; i < obj.length; i++) {
                        $('#cabangtujuan').append('<option value="'+ obj[i].id +'">'+ obj[i].cabang_name +'</option>');
                    }
                })
            },
            error:function(error){
                console.log(error);
            }
        });
    }

    function onChangeAssetKondisi(obj){
        // console.log(obj);
        var idasset = obj.value;
        // console.log(assetcabang);
        $.ajax({
            method  : "POST",
            url     : "{{url('/api/assethis')}}",
            data    :{
                "_token"  : "{{ csrf_token() }}",
                "idasset" : idasset
            },

            success:function(response) {
                console.log(response);

                /*reset select loc_kota*/
                $('#idkondisiawal').val('');
                $('#kondisiawal').val('');
                // append - default value
                // $('#cabangtujuan').append('<option value="">Choose Cabang</option>');
                // append - each response
                $.each(response, function(index, obj){
                    for (let i = 0; i < obj.length; i++) {
                        $('#idkondisiawal').val(obj[i].id_kondisi_akhir);
                        $('#kondisiawal').val(obj[i].condition_name);
                        // $('#cabangtujuan').append('<option value="'+ obj[i].id +'">'+ obj[i].cabang_name +'</option>');
                    }
                })
            },
            error:function(error){
                console.log(error);
            }
        });
    }

    function onChangeteamasset(){
        // console.log(obj);
        // var team = obj.value;
        // console.log(tipe);
        $.ajax({
            method  : "POST",
            url     : "{{url('/api/teamasset')}}",
            data    :{
                "_token"  : "{{ csrf_token() }}"
            },

            success:function(response) {
                console.log(response);

                /*reset select loc_kota*/
                $('#team').empty();

                // append - default value
                $('#team').append('<option value="">Choose Team</option>');
                // append - each response
                $.each(response, function(index, obj){
                    for (let i = 0; i < obj.length; i++) {
                        $('#team').append('<option value="'+ obj[i].id +'">'+ obj[i].team_asset_name +'</option>');
                    }
                })
            },
            error:function(error){
                console.log(error);
            }
        });

        
    }
    
    function onChangejabatanasset(){
        // console.log(obj);
        // var jabatan = obj.value;
        // console.log(tipe);
        $.ajax({
            method  : "POST",
            url     : "{{url('/api/jabatanasset')}}",
            data    :{
                "_token"  : "{{ csrf_token() }}"
            },

            success:function(response) {
                console.log(response);

                /*reset select loc_kota*/
                $('#idjabatanasset').empty();

                // append - default value
                $('#idjabatanasset').append('<option value="">Choose jabatan</option>');
                // append - each response
                $.each(response, function(index, obj){
                    for (let i = 0; i < obj.length; i++) {
                        $('#idjabatanasset').append('<option value="'+ obj[i].id +'">'+ obj[i].jabatan_asset_name +'</option>');
                    }
                })
            },
            error:function(error){
                console.log(error);
            }
        });

        
    }

    // alert
    window.setTimeout(function() {
    $("#alert-success").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); });
    }, 3000);
    window.setTimeout(function() {
        $("#alert-error").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove(); });
    }, 3000);

    // validasi - number only
    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
        return true;
    }
    // validasi - decimal only
    function isDecimalKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        // allow (.)
        if(charCode == 46) return true;
        // only allow number
        if ((charCode > 31 && (charCode < 48 || charCode > 57)))
        return false;
        return true;
    }
    // validasi - name space
    $(".isNameSpace").keypress(function(event){
        var inputValue = event.which;
        // allow letters and whitespaces only.
        if(!(inputValue >= 65 && inputValue <= 122) && (inputValue != 32 && inputValue != 0)) {
            event.preventDefault();
        }
    });
    function isAplhaNumberKey(evt) {
        inputValue = event.which;
        var arrCharSpaceAllow = [
            113, 119, 101, 114, 116, 121, 117, 105, 111, 112,
            97, 115, 100, 102, 103, 106, 107, 108, 104, 122,
            120, 99, 118, 98, 110, 109, 32
        ];
        var arrNumberAllow = [
            49, 50, 51, 52, 53, 54, 55, 56, 57, 48
        ];
        var checkChar = arrCharSpaceAllow.includes(inputValue);
        var checkNum  = arrNumberAllow.includes(inputValue);

        if(checkChar || checkNum) return true
        return false
    }
    // validasi - uang
    function isCurrency(obj){
        var val = obj.value;
        val     = val.replace(/[^0-9\.]/g,'');

        if(val != "") {
            valArr = val.split('.');
            valArr[0] = (parseInt(valArr[0],10)).toLocaleString();
            val = valArr.join('.');
        }

        obj.value = val;
    }

    function showModalAlert(msg){
        $('#modal-alert-msg').html(msg);
        $('#modal-alert').modal('show');
    }

    // on submit form
    function onSubmit(url){
        $('#modal-alert-code').val('');
        var datastring = $("#form-submit").serialize();
        if (confirm("Apakah Anda Yakin ?")) {
        // your deletion code
          // onSubmit('{{url("transaksi/realisasi-perpindahan-asset/store")}}');
  
                $.ajax({
                    type: "POST",
                    url: url,
                    data: datastring,
                    dataType: "json",
                    success: function(data) {
                        if(data.code == 1){
                            $('#modal-alert-code').val(1);
                            showModalAlert(data.msg);
                        } else {
                            $('#modal-alert-code').val(0);
                            showModalAlert(data.msg);
                        }
                    },
                    error: function() {
                        $('#modal-alert-code').val(0);
                        showModalAlert(data.msg);
                    }
                    });

          }
          return false;
    }

     function onCheckDateFromTo(){
        var datefrom = $('#datefrom').val();
        var dateto = $('#dateto').val();
        var f_datefrom = datefrom.replace(/-/g, '');
        var f_dateto = dateto.replace(/-/g, '');
        console.log(f_datefrom,f_dateto);



        if(f_datefrom.length > 0 && f_dateto.length > 0 && f_datefrom > f_dateto){
            showModalAlert('Tanggal Date from Harus lebih kecil dari tanggal Date To.')
             $('#dateto').val(null).trigger('change');
             $('#datefrom').val(null).trigger('change');
        }

        
    }

    function onCheckDateFromAssetTo(){
        var tglpengdaan = $('#tglpengdaan').val();
        var tglterima = $('#tglterima').val();
        var f_datefrom = tglpengdaan.replace(/-/g, '');
        var f_dateto = tglterima.replace(/-/g, '');
        console.log(f_datefrom,f_dateto);

        if(f_dateto.length > 0 && f_datefrom.length > 0 &&  f_datefrom > f_dateto){
            showModalAlert('Tanggal Pengadaan Harus lebih kecil dari tanggal terima.')
             $('#tglpengdaan').val(null).trigger('change');
             $('#tglterima').val(null).trigger('change');
        }
    }


</script>

