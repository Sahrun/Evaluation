
var navigation = new Vue({
    el: '#navigation',
    data: {
    Navigation:[]
    },
    mounted: function () {
      this.GetEvaluations();
    },
    methods: {
      GetEvaluations: function () 
        {
          $('#load').show();
          axios.get('http://localhost/Evaluation/Api/UserService.php?route=getnavigation')
          .then(response => {
              this.Navigation = response.data;
              $('#load').hide();
          })
          .catch((err) => {
            console.log(err);
          });
      },
    }
});
