var evaluation = new Vue({
    el: '#evaluation',
    data: {
    Evaluations:[],
    PRVendors:[],
    ItemEvaluation:[],
    Evaluation:{},
    IDR:[],
    Submitted:false
    },
    mounted: function () {
      var url = new URL(window.location);
      var key = url.searchParams.get("key");
      var view = url.searchParams.get("view");
      var approve = url.searchParams.get("approve");
      if(key != null && key != undefined && key != "")
      {
          this.Evaluation = {
               PRId           : key,
               PR             : null,
               ItemEvaluation : null,
               PRVendor       : null,
               Note           : null
          };
          this.GetPR(key);
      }
      else if(view != null && view != undefined && view != "")
      {
         this.GetEvaluationView(view);
      }
      else if(approve != null && approve != undefined && approve != "")
      {
        this.GetEvaluationApproveView(approve);
      }
      else
      {
        this.GetEvaluations();
      }
    },
    methods: {
        GetEvaluations: function ()
        {
          axios.get('http://localhost/Evaluation/Api/EvaluationService.php?route=getevaluations')
          .then(response => {
              this.Evaluations = response.data;
          })
          .catch((err) => {
             console.log(err.data)
          });
        },
        GetPR: function (key)
        {
          $('#load').show();
          axios
      		.get('http://localhost/Evaluation/Api/PRService.php?route=getprdetail&id='+key+'')
      		.then(response => {
              this.Evaluation.PR = response.data;
              $('#load').hide();
              this.GetEvalVendorDet(key);
          });
        },
        GetEvalVendorDet: function (key)
        {
          $('#load').show();
          axios
      		.get('http://localhost/Evaluation/Api/EvaluationService.php?route=getevalvendordet&PRId='+key+'')
      		.then(response => {
              this.PRVendors = response.data;
              $('#load').hide();
              this.GetEvalItemDet(key);
          });
        },
        GetEvalItemDet: function (key)
        {
          $('#load').show();
          axios
          .get('http://localhost/Evaluation/Api/EvaluationService.php?route=getevalitemdet&PRId='+key+'')
          .then(response => {
            this.ItemEvaluation = response.data;
            this.GetIDR();
            $('#load').hide();
          });
        },
        GetIDR:function ()
        {
          $('#load').show();
          axios
          .get('http://localhost/Evaluation/Api/IDRService.php?route=getidr')
          .then(response => {
            this.IDR = response.data
            $('#load').hide();
          });
        },
        SelectWinner:function (PRVendorId,Selected)
        {
          this.PRVendors.forEach(x => {
              if(x.PRVendorId == PRVendorId){
                x.Disabled = false;
                x.IsWinner = Selected;
                x.SelectedChild = Selected;
                if(Selected == false)
                {
                  x.Exclude = [];
                  x.Include = [];
                }
              }else{
                x.Disabled = Selected;
                x.IsWinner = false;
              }
          });
          this.ItemVendorDisabled(PRVendorId,Selected);
        },
        ItemVendorDisabled: function(PRVendorId,Selected)
        {
            this.ItemEvaluation.forEach(function (item,key){
               item.Vendor.forEach(x => {
                  if(x.PRVendorId == PRVendorId){
                    x.Disabled = false;
                    x.Selected = Selected;
                  }else{
                     x.Disabled = Selected;
                     x.Delivery = null;
                     x.UnitPrice = null;
                     x.TotalPrice = null;
                     x.Description = null;
                     x.Selected = false;
                  }
               })
            });

        },
        SelectedWinnerVendor(PRVendorId,Item,Selected)
        {
           Item.Vendor.forEach(x => {
                if(x.PRVendorId == PRVendorId){
                    x.Disabled = false;
                    x.Selected = Selected;
                    if(!Selected){
                      x.Selected = false;
                      x.Delivery = null;
                      x.UnitPrice = null;
                      x.TotalPrice = null;
                      x.Description = null;
                      var Winner = this.PRVendors.filter(c => c.PRVendorId == PRVendorId);
                      if(Winner.length > 0){
                          Winner[0].Exclude = [];
                          Winner[0].Include = [];
                      }
                    }
                }else{
                  if(Selected ==  false){
                    var Winner = this.PRVendors.filter(c => c.PRVendorId == x.PRVendorId);
                    if(Winner.length > 0){
                       x.Disabled = Selected;
                        Winner[0].Disabled = Selected;
                    }
                  }else{
                    x.Disabled  = Selected;
                  }
                  x.Selected = false;
                  x.Delivery = null;
                  x.UnitPrice = null;
                  x.TotalPrice = null;
                  x.Description = null;
                }
           });

          var this_vendor = this.PRVendors.filter(x => x.PRVendorId == PRVendorId);
          var item_this_vendor = this.ItemEvaluation.filter(x => x.Vendor.PRVendorId == PRVendorId);

          if(item_this_vendor.length == this.ItemEvaluation.length){
            if(this_vendor.length > 0){
              this_vendor[0].IsWinner = true;
              this_vendor[0].Disabled = false;
            }
          }

          // Check Vendor Winner
          var countItem = this.ItemEvaluation.length;

          this.PRVendors.forEach(x => {
            var total  = 0;
               this.ItemEvaluation.forEach(i => {
                  total = total + i.Vendor.filter(c => c.Selected == true && c.PRVendorId == x.PRVendorId).length;
               });
            if(total == countItem){
              this.SelectWinner(x.PRVendorId,true);
              x.SelectedChild = true;
            }else if(total > 0){
              x.SelectedChild = true;
              x.IsWinner = false;
            }else if(total == 0){
              x.SelectedChild = false;
              x.IsWinner = false;
            }
          });

        this.CalculateTotal();
        },
       CalculateTotal: function()
       {
          this.PRVendors.forEach(v => {
            var SubTotal = 0;
              this.ItemEvaluation.forEach(x => {
                  x.Vendor.forEach(c => {
                    c.TotalPrice = parseInt(x.Qty) * (c.UnitPrice == null || c.UnitPrice == ""? 0: parseInt(c.UnitPrice));
                    if(c.PRVendorId == v.PRVendorId){
                        SubTotal += c.TotalPrice;
                    }
                  })
              });
              v.EvaluationDetail.SubTotal  = SubTotal;
              // Discount
              if(v.EvaluationDetail.Discount != null && v.EvaluationDetail.Discount != undefined && v.EvaluationDetail.Discount != ""){
                var dic = parseInt(v.EvaluationDetail.Discount) / 100;
                v.EvaluationDetail.Total = v.EvaluationDetail.SubTotal * dic;
              }
              //
              // PPN
              if(v.EvaluationDetail.Total != null && v.EvaluationDetail.Total != undefined && v.EvaluationDetail.Total != ""){
                 var ppn = 10 / 100;
                 v.EvaluationDetail.PPN = v.EvaluationDetail.Total * ppn;
              }
              //
              // Grand Total
              if(v.EvaluationDetail.PPN != null && v.EvaluationDetail.PPN != undefined && v.EvaluationDetail.PPN != ""){
                 v.EvaluationDetail.GrandTotal = v.EvaluationDetail.Total + v.EvaluationDetail.PPN;
              }
              //
          });
       },
      AddInclude:function(index)
      {
        this.PRVendors[index].Include.push({
                  IncludeId:null,
                  ItemName:null,
                  EvaluationId:null
          });
      },
      AddExclude:function(index)
      {
        this.PRVendors[index].Exclude.push({
                  ExcludeId:null,
                  ItemName:null,
                  EvaluationId:null
        });
      },
      DeleteInclude:function(include,index)
      {
        include.splice(index,1);
      },
      DeleteExclude:function(exclude,index)
      {
        exclude.splice(index,1);
      },
      Submit: function()
      {
        this.Submitted = true;
        var Valid = true;

        this.ItemEvaluation.forEach(x => {
            var Required =  x.Vendor.filter(c => this.IsValueNull(c.Delivery) || this.IsValueNull(c.Delivery) || this.IsValueNull(c.TotalPrice) || this.IsValueNull(c.UnitPrice));
            if(Required.length > 0)
            {
              Valid = false;
            }
        });

        if(Valid == true)
          {
              this.PRVendors.forEach(x => {
              var invalidInclude =  x.Include.filter(c => this.IsValueNull(c.ItemName));
              var invalidExclude =  x.Exclude.filter(c => this.IsValueNull(c.ItemName));
              if(this.IsValueNull(x.EvaluationDetail.IDRId) || this.IsValueNull(x.EvaluationDetail.PaymentTerms) || this.IsValueNull(x.EvaluationDetail.DeliveryPoint) || invalidInclude.length > 0 || invalidExclude.length > 0)
                {
                  Valid = false;
                }
              });
          }

          if(Valid == false)
          {
            alert("Field tidak boleh kosong");
          }else
          {
            $('#load').show();
            this.Evaluation.ItemEvaluation = this.ItemEvaluation;
            this.Evaluation.PRVendor = this.PRVendors;
            axios.post('http://localhost/Evaluation/Api/EvaluationService.php',
              this.Evaluation,
            {
              headers:
              {
                'Content-type': 'application/x-www-form-urlencoded',
                }
            }).then(response => {
              $('#load').hide();
              window.location.href = "http://localhost/Evaluation/pages/PR/";
            })
            .catch((err) => {
              console.log(err.data)
            });
          }
      },
      IsValueNull(value){
        var Isnull = false;
        if(value == undefined || value == null || value == "" || value == 0)
        {
          Isnull = true;
        }
        return Isnull;
      },
      GetEvaluationView: function(evaluationId)
      {
          $('#load').show();
          axios
          .get('http://localhost/Evaluation/Api/EvaluationService.php?route=getevaluationview&evaluationId='+evaluationId+'')
          .then(response => {
            this.Evaluation = response.data;
            $('#load').hide();
          })
          .catch((err) => {
           console.log(err.data)
          });
      },
      GetEvaluationApproveView: function(evaluationId)
      {
          axios
          .get('http://localhost/Evaluation/Api/EvaluationService.php?route=getevaluationapproval&evaluationId='+evaluationId+'')
          .then(response => {
            this.Evaluation = response.data;
          })
          .catch((err) => {
           console.log(err.data)
          });
      },
      Approve: function()
      {
        axios.post('http://localhost/Evaluation/Api/EvaluationService.php?route=approve',
         this.Evaluation,
        {
          headers:
          {
           'Content-type': 'application/x-www-form-urlencoded',
           }
        }).then(response => {
          console.log(response);
           // window.location.href = "http://localhost/Evaluation/pages/Evaluation/Index.php";
        })
        .catch((err) => {
           console.log(err.data)
        });
      },
      Print:function()
      {
        window.open('http://localhost/Evaluation/Api/Print.php?route=evaluationprint&id='+this.Evaluation.EvaluationId+'', '_blank');
      }
    }
});
