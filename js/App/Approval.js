
var evaluation = new Vue({
    el: '#approval',
    data: {
    Evaluations:[],
    Evaluation:{},
    },
    mounted: function () {
      var url = new URL(window.location);
      var approve = url.searchParams.get("approve");
      if(approve != null && approve != undefined && approve != "")
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
        $('#load').show();
        axios.get('http://localhost/Evaluation/Api/EvaluationService.php?route=getevaluations')
        .then(response => {
          $('#load').hide();
          this.Evaluations = response.data;
        })
        .catch((err) => {
           console.log(err.data)
        });
      },
      GetEvaluationApproveView: function(evaluationId)
      {
          $('#load').show();
          axios
          .get('http://localhost/Evaluation/Api/EvaluationService.php?route=getevaluationapproval&evaluationId='+evaluationId+'')
          .then(response => {
            $('#load').hide();
            this.Evaluation = response.data; 
          })
          .catch((err) => {
           console.log(err.data)
          });
      },
      Approve: function()
      {
        console.log(this.Evaluation);
        var valid = false;
        this.Evaluation.Evaluationdetailvendor.forEach(x => {
          if(x.Vendor.filter(x => x.Selected == true).length){
            valid = true;
          }
        });
        if(valid == false)
        {
          alert("Harus memilih minimal satu");
        }
        else
        {
            $('#load').show();
            this.Evaluation.CurrentApproval.Status = 1;
            axios.post('http://localhost/Evaluation/Api/EvaluationService.php?route=approve', 
            this.Evaluation,
            { 
              headers: 
              {
              'Content-type': 'application/x-www-form-urlencoded',
              }
            }).then(response => {
              console.log(response);
              $('#load').hide();
              window.location.href = "http://localhost/Evaluation/pages/Approval/Index.php";
            })
            .catch((err) => {
              console.log(err.data)
            });
        }
      },
      SelectWinner:function (PRVendorId,Selected)
      {
        this.Evaluation.PRVendor.forEach(x => {
            if(x.PRVendorId == PRVendorId){
              x.Disabled = false;
              x.IsWinner = Selected;
              x.SelectedChild = Selected;
            }else{
              x.Disabled = Selected;
              x.IsWinner = false;
            }
        });
        this.ItemVendorDisabled(PRVendorId,Selected);
      },
      ItemVendorDisabled: function(PRVendorId,Selected)
      {
          this.Evaluation.Evaluationdetailvendor.forEach(function (item,key){
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
                    var Winner = this.Evaluation.PRVendor.filter(c => c.PRVendorId == PRVendorId);
                  }
              }else{
                if(Selected ==  false){
                  var Winner = this.Evaluation.PRVendor.filter(c => c.PRVendorId == x.PRVendorId);
                  if(Winner.length > 0){
                     x.Disabled = Selected;
                      Winner[0].Disabled = Selected;
                  }
                }else{
                  x.Disabled  = Selected;
                }
                x.Selected = false;
              }
         });

        var this_vendor = this.Evaluation.PRVendor.filter(x => x.PRVendorId == PRVendorId);
        var item_this_vendor = this.Evaluation.Evaluationdetailvendor.filter(x => x.Vendor.PRVendorId == PRVendorId);

        if(item_this_vendor.length == this.Evaluation.Evaluationdetailvendor.length){
          if(this_vendor.length > 0){
            this_vendor[0].IsWinner = true;
            this_vendor[0].Disabled = false;
          }
        }

        // Check Vendor Winner
        var countItem = this.Evaluation.Evaluationdetailvendor.length;

        this.Evaluation.PRVendor.forEach(x => {
          var total  = 0;
          this.Evaluation.Evaluationdetailvendor.forEach(i => {
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
      }
    }
});
