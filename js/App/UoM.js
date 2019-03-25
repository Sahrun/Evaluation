const { required, minLength } = window.validators
var uom = new Vue({
    el: '#uom',
    data: {
      UoM:{
        UoMId   : null,
        UoMKode : null,
        UoMName : null,
        Status  : 'Active'
      },
      UoMs:[],
      Submitted:false
    },
    validations:{
        UoM:{
            UoMKode : {
               required 
            },
            UoMName : {
                required
            },
            Status  : {
                required
            }
        }
    },
    mounted: function () {
      this.GetUoM();
    },
    methods: {
     NewUoM:function()
     {
        this.Submitted = false;
     	this.UoM = {
     		UoMId   : null,
     		UoMKode : null,
     		UoMName : null,
     		Status  : 'Active'
     	}
     	$('#UoMModal').modal('show');
     },
     Submit:function()
     {
        this.$v.$touch();
        this.Submitted = true;
        if(!this.$v.$invalid){
           if(this.UoM.UoMId != null && this.UoM.Status == 'Non-Active')
           {
                var r = confirm("Apakah anda akan meng non-aktivkan");
                if (r == true)
                {
                   this.SaveUoM();
                }
            }
            else 
            {
                this.SaveUoM();
            }
        }
     },
     SaveUoM:function()
     {
        $('#load').show();
     	axios.post('http://localhost/Evaluation/Api/UoMService.php', 
         this.UoM,
        { 
          headers: 
          {
           'Content-type': 'application/x-www-form-urlencoded',
           }
        }).then(response => {
           $('#load').hide();
           $('#UoMModal').modal('hide');
           this.GetUoM();
        })
        .catch((err) => {
           console.log(err.data);
           alert("Proses Gagal");
        });
     },
     GetUoM:function()
     {
        $('#load').show();
     	axios.get('http://localhost/Evaluation/Api/UoMService.php?route=getuom')
        .then(response => {
            this.UoMs = response.data;
            $('#load').hide();
        })
        .catch((err) => {
           console.log(err.data)
        });
     },
     Edit:function(UoMId)
     {
        $('#load').show();
     	axios.get('http://localhost/Evaluation/Api/UoMService.php?route=edit&id='+UoMId+'')
        .then(response => {
            this.UoM = response.data;
            this.Submitted = false;
            $('#load').hide();
            $('#UoMModal').modal('show');
        })
        .catch((err) => {
           console.log(err.data)
        });
     }
    }
});
