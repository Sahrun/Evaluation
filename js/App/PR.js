const { required, between } = window.validators
var pr = new Vue({
    el: '#pr',
    data: {
     PR:{
       PRId:null,
       PRName:null,
       Project:null,
       PRCode:null,
       CostCenter:null,
       Colective:null,
       PurchaseCode:null,
       IsEvaluation:null,
       PRVendor:null,
       PRItem:null
     },
     PRView:{},
     PRs:[],
     Items:[],
     UoMs:[],
     Vendors:[],
     PRItem:[],
     PRVendor:[],
     Submitted:false
    },
    validations:{
        PR:{
            PRName : {
               required 
            },
            Project  : {
                required
            }
        },
        PRItem:{
          required,
          $each:{
            Qty:{
              required,
              between: between(1, 100)
            },
            ItemId:{
              required
            },
            UoMId:{
              required
            }
          }
        },
        PRVendor:{
          required,
          $each:{
            VendorId:{
              required
            }
          }
        }
    },
    mounted: function () {
        var url = new URL(window.location);
        var key = url.searchParams.get("key");

        if(key != null && key != undefined && key != "")
        {
          // this.Evaluation = {
          //      PRId : key,
          //      ItemEvaluation : null,
          //      VendorInvitation:null,
          //      Note:null
          // };
          // this.GetListInvitationvendor(key);
          // this.GetListItem(key);
        }
        else
        {
           this.GetPR();
        }
    },
    methods: {
         GetListItem: function () {
          $('#load').show();
            axios
      		.get('http://localhost/Evaluation/Api/ItemService.php?route=getitem')
          .then(response => {
             this.Items = response.data;
             this.GetListUom();
          })
          .catch((err) => {
             console.log(err)
          });
        },
        GetListUom: function(){
        	axios
      		.get('http://localhost/Evaluation/Api/UoMService.php?route=getuom')
      		.then(response => { 
            this.UoMs = response.data;
            this.GetListVendor();
          })
          .catch((err) => {
             console.log(err.data)
          });
        },
        GetListVendor: function(){
        	axios
      		.get('http://localhost/Evaluation/Api/VendorService.php?route=getvendor')
      		.then(response => {
            this.Vendors = response.data;
            $('#load').hide();
          })
          .catch((err) => {
             console.log(err.data)
          });
        },
        GetPR: function(){
          $('#load').show();
          axios
          .get('http://localhost/Evaluation/Api/PRService.php?route=getpr')
          .then(response => {
            this.PRs = response.data;
            $('#load').hide();
          })
          .catch((err) => {
             console.log(err.data)
          });
        },
        NewPR:function()
        {
          this.Submitted = false;
          this.PR ={
            PRId:null,
            PRName:null,
            Project:null,
            PRCode:null,
            CostCenter:null,
            Colective:null,
            PurchaseCode:null,
            IsEvaluation:null,
            PRVendor:null,
            PRItem:null,
          };
          this.PRVendor =[];
          this.PRitem =[];

          this.GetListItem();
          $("#PRModal").modal('show');
        },
        NewPritem: function(){
           this.PRItem.push({PRItemId:null,Qty:null,ItemId:null,PRId:null,UoMId:null});
        },
        DeleteItem: function(index){
          this.PRItem.splice(index,1);
        },
        NewVendor: function(){
          this.PRVendor.push({VendorId:null,VendorName:null,Address :null });
        },
        DeleteVendor: function(index){
          this.PRVendor.splice(index,1);
        },
        SetVendor: function(vendorId,index){
        	var vendor = this.Vendors.filter(v => v.VendorId == vendorId);
        	if(vendor !== null){
        	  this.PRVendor[index].Address = vendor[0].Address;
        	}
        },
        Submit: function()
        {
          this.$v.$touch();
          this.Submitted = true;
          if(!this.$v.$invalid)
          {
            if(!this.ValidasItem())
            {
              alert("Item tidak boleh ada yang sama !");
            }
            else if(!this.ValidasiVendor())
            {
              alert("Vendor tidak boleh ada yang sama !");
            }
            else 
            {
              this.PR.PRVendor = this.PRVendor;
              this.PR.PRItem = this.PRItem;
              this.SavePR();
            }
          }
        },
        SavePR:function()
        {
           $('#load').show();
           axios.post('http://localhost/Evaluation/Api/PRService.php', 
             this.PR,
            { 
              headers: 
              {
               'Content-type': 'application/x-www-form-urlencoded',
               }
            }).then(response => {
               $('#load').hide();
               $('#PRModal').modal('hide');
               this.GetPR();
            })
            .catch((err) => {
               console.log(err.data);
               alert("Proses Insert");
            });
        },
        ValidasItem:function()
        {
          var isvalid = true;
           var BreakException = {};
           try {
              this.PRItem.forEach(i => {
                  var exis = this.PRItem.filter(x => x.ItemId == i.ItemId);
                  if(exis.length > 1){
                    isvalid = false;
                    throw BreakException;
                  }
              });
            } catch (e) {
              if (e !== BreakException) throw e;
            }
          return isvalid;
        },
        ValidasiVendor:function()
        {
          var isvalid = true;
           var BreakException = {};
           try {
              this.PRVendor.forEach(i => {
                  var exis = this.PRVendor.filter(x => x.VendorId == i.VendorId);
                  if(exis.length > 1){
                    isvalid = false;
                    throw BreakException;
                  }
              });
            } catch (e) {
              if (e !== BreakException) throw e;
            }
          return isvalid;
        },
        View:function(PRId)
        {
           $('#load').show();
           axios.get('http://localhost/Evaluation/Api/PRService.php?route=getprdetail&id='+PRId+'')
          .then(response => {
              this.PRView = response.data;
              $('#load').hide();
              $('#PRModalView').modal('show');
          })
          .catch((err) => {
             console.log(err.data)
          });
        }
    }
});