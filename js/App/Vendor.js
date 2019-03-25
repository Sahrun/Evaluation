const { required, minLength } = window.validators
var vendor = new Vue({
    el: '#vendor',
    data: {
      Vendor:{
        VendorId   : null,
        VendorName : null,
        Address    : null,
        Status     : 'Active'
      },
      Vendors:[],
      Submitted:false
    },
    validations:{
        Vendor:{
            VendorName : {
               required 
            },
            Address  : {
                required
            },
            Status :{
                required
            }
        }
    },
    mounted: function () {
      this.GetVendor();
    },
    methods: {
     NewVendor:function()
     {
        this.Submitted = false;
     	this.Vendor = {
         		 VendorId   : null,
                 VendorName : null,
                 Address    : null,
                 Status     : 'Active'
     	}
     	$('#VendorModal').modal('show');
     },
     Submit:function()
     {
        this.$v.$touch();
        this.Submitted = true;
        if(!this.$v.$invalid){
         	if(this.Vendor.VendorId != null && this.Vendor.Status == 'Non-Active')
         	{
    	     	var r = confirm("Apakah anda akan meng non-aktivkan");
    			if (r == true)
    			{
    			   this.SaveVendor();
    			}
         	}
         	else 
         	{
         		this.SaveVendor();
         	}
        }
     },
     SaveVendor:function()
     {
        $('#load').show();
     	axios.post('http://localhost/Evaluation/Api/VendorService.php', 
         this.Vendor,
        { 
          headers: 
          {
           'Content-type': 'application/x-www-form-urlencoded',
           }
        }).then(response => {
           $('#load').hide();
           $('#VendorModal').modal('hide');
           this.GetVendor();
        })
        .catch((err) => {
           console.log(err.data)
        });
     },
     GetVendor:function()
     {
        $('#load').show();
     	axios.get('http://localhost/Evaluation/Api/VendorService.php?route=getvendor')
        .then(response => {
            this.Vendors = response.data;
            $('#load').hide();
        })
        .catch((err) => {
           console.log(err.data)
        });
     },
     Edit:function(VendorId)
     {
        $('#load').show();
     	axios.get('http://localhost/Evaluation/Api/VendorService.php?route=edit&id='+VendorId+'')
        .then(response => {
            this.Vendor = response.data;
            this.Submitted = false;
            $('#load').hide();
            $('#VendorModal').modal('show');
        })
        .catch((err) => {
           console.log(err.data)
        });
     }
    }
});
