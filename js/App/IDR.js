const { required, minLength } = window.validators
var IDR = new Vue({
    el: '#IDR',
    data: {
      IDR:{
         IDRId: null,
         IDRCode: null,
         Description: null,
         Status: 'Active',
      },
      IDRs:[],
      Submitted:false
    },
    validations:{
        IDR:{
            IDRCode : {
               required 
            },
            Status  : {
                required
            }
        }
    },
    mounted: function () {
      this.GetIDR();
    },
    methods: {
     NewIDR:function()
     {
        this.Submitted = false;
     	this.IDR = {
            IDRId: null,
            IDRCode: null,
            Description: null,
            Status: 'Active',
     	}
     	$('#IDRModal').modal('show');
     },
     Submit:function()
     {
        this.$v.$touch();
        this.Submitted = true;
        if(!this.$v.$invalid){
         	if(this.IDR.IDRId != null && this.IDR.Status == 'Non-Active')
         	{
    	     	var r = confirm("Apakah anda akan meng non-aktivkan");
    			if (r == true)
    			{
    			   this.SaveIDR();
    			}
         	}
         	else 
         	{
         		this.SaveIDR();
         	}
        }
     },
     SaveIDR:function()
     {
        $('#load').show();
     	axios.post('http://localhost/Evaluation/Api/IDRService.php', 
         this.IDR,
        { 
          headers: 
          {
           'Content-type': 'application/x-www-form-urlencoded',
           }
        }).then(response => {
           $('#load').hide();
           $('#IDRModal').modal('hide');
           this.GetIDR();
        })
        .catch((err) => {
           console.log(err.data)
        });
     },
     GetIDR:function()
     {
        $('#load').show();
     	axios.get('http://localhost/Evaluation/Api/IDRService.php?route=getidr')
        .then(response => {
            this.IDRs = response.data;
            $('#load').hide();
        })
        .catch((err) => {
           console.log(err.data)
        });
     },
     Edit:function(IDRId)
     {
        $('#load').show();
     	axios.get('http://localhost/Evaluation/Api/IDRService.php?route=edit&id='+IDRId+'')
        .then(response => {
            this.IDR = response.data;
            this.Submitted = false;
            $('#load').hide();
            $('#IDRModal').modal('show');
        })
        .catch((err) => {
           console.log(err.data)
        });
     }
    }
});
