<?php
$SERVER = $_SERVER['DOCUMENT_ROOT'];
include $SERVER."/Evaluation/Config/ConfigHead.php";
?>
<div class="main-panel" id="evaluation">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h2 class="card-title"><strong>Daftar Evaluasi </strong></h2>
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>
                      <strong> No </strong>
                    </th>
                    <th>
                      <strong> Code Evaluasi </strong>
                    </th>
                    <th>
                      <strong> Nama PR </strong>
                    </th>
                    <th>
                      <strong>Project</strong>
                    </th>
                    <th>
                      <strong>Status Evaluasi</strong>
                    </th>
                    <th>
                      <strong>Action</strong>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(evaluation,index) in Evaluations">
                    <td>{{index+1}}</td>
                    <td>{{evaluation.EvaluationCode}}</td>
                    <td>{{evaluation.PRName}}</td>
                    <td>{{evaluation.Project}}</td>
                    <td>Dalam Proses</td>
                    <td>
                      <a class='btn btn-primary btn-sm' :href="'view.php?view='+evaluation.EvaluationId">Detail</a>
                      <a class='btn btn-primary btn-sm' :href="'<?php echo $_ROOT ?>pages/Approval/Approval.php?approve='+evaluation.EvaluationId">Approval</a></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row text-right">
      <div class="col-md-12">
        <input type="button" value="Cancel"  class="btn  btn-lg btn-default" onclick="goBack()">
      </div>
    </div>
  </div>
  <?php include $SERVER."/Evaluation/Config/ConfigFooter.php"; ?>
  <script src="../../js/App/Evaluation.js"></script>