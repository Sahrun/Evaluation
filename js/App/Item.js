const { required, minLength } = window.validators
var item = new Vue({
    el: '#item',
    data: {
      Item:{
        ItemName    : null,
        Status      : 'Active',
        Description : null
      },
      Items:[],
      Submitted:false
    },
    validations:{
        Item:{
            ItemName : {
               required 
            },
            Status  : {
                required
            }
        }
    },
    mounted: function () {
      this.GetItem();
    },
    methods: {
     NewItem:function()
     {
        this.Submitted = false;
     	this.Item = {
     		ItemId      : null,
            ItemName    : null,
            Status      : 'Active',
            Description : null
     	}
     	$('#ItemModal').modal('show');
     },
     Submit:function()
     {
        this.$v.$touch();
        this.Submitted = true;
        if(!this.$v.$invalid){
         	if(this.Item.ItemId != null && this.Item.Status == 'Non-Active')
         	{
    	     	var r = confirm("Apakah anda akan meng non-aktivkan");
    			if (r == true)
    			{
    			   this.SaveItem();
    			}
         	}
         	else 
         	{
         		this.SaveItem();
         	}
        }
     },
     SaveItem:function()
     {
        $('#load').show();
     	axios.post('http://localhost/Evaluation/Api/ItemService.php', 
         this.Item,
        { 
          headers: 
          {
           'Content-type': 'application/x-www-form-urlencoded',
           }
        }).then(response => {
           this.GetItem();
           $('#load').hide();
           $('#ItemModal').modal('hide');
        })
        .catch((err) => {
           console.log(err.data)
        });
     },
     GetItem:function()
     {
        $('#load').show();
     	axios.get('http://localhost/Evaluation/Api/ItemService.php?route=getitem')
        .then(response => {
            this.Items = response.data;
            $('#load').hide();
        })
        .catch((err) => {
           console.log(err.data)
        });
     },
     Edit:function(ItemId)
     {
        $('#load').show();
     	axios.get('http://localhost/Evaluation/Api/ItemService.php?route=edit&id='+ItemId+'')
        .then(response => {
            this.Item = response.data;
            this.Submitted = false;
            $('#load').hide();
            $('#ItemModal').modal('show');
        })
        .catch((err) => {
           console.log(err.data)
        });
     }
    }
});
