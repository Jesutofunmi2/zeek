

<div class="row">
    <div class="col-sm-12">
        <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-info"></i> Quick Info!</h4>
        Keep record of all payouts. 
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-10" >
		<div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Payout</h3>
             
            </div>
            <!-- /.box-header -->
            <div class="box-body">
             
             
                      <form  enctype="multipart/form-data" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER['SCRIPT_NAME']); ?>" method="post" >
                            
                                                  
                             <div class="form-group">                       
                               <div class="col-sm-4">
                                    <label for="scope">Select type &nbsp;</label>
                                    <select class="form-control" id="scope" name="scope">
                                        <option value="1" >Driver</option>
                                        <option value="2" >Franchise</option>
                                        <option value="3" >Partner</option>
                                        <option value="4" >Others</option>
                                        
                                    </select>
                                </div>                              
                             </div>

                             <div class="form-group">
                                <div class="col-sm-4">
                                                                        
                                    <div id="type-driver" style='display:none'>
                                        <label>Driver name &nbsp;</label>
                                        <input  type="text" class="form-control" id="booking-driver" placeholder="" name="driver-scope-name" value="" >
                                        <input  type="text" hidden='hidden' id="booking-driverid" placeholder="" name="driver-scope-id" value="" >
                                    </div>

                                    <div id="type-franchise" style='display:none'>
                                        <label>Franchise name &nbsp;</label>
                                        <select class="form-control" id="franchise-scope-name" name="franchise-scope-name">
                                            
                                            <?php 
                                                
                                                foreach($franchise_data as $franchisedata){
                                                
                                            ?>   
                                            <option value="<?php echo urlencode($franchisedata['franchise_name']) ?>" ><?php echo $franchisedata['franchise_name'] ?></option>  
                                            <?php } ?>                                            
                                        </select>
                                        
                                    </div>

                                    <div id="type-partner" style='display:none'>
                                        <label>Partner name &nbsp;</label>
                                        <input  type="text" class="form-control" id="partner-name" placeholder="" name="partner-name" value="" >
                                    </div>

                                     <div id="type-others" style='display:none'>
                                        <label>Enter title &nbsp;</label>
                                        <input  type="text" class="form-control" id="others-name" placeholder="" name="others-name" value="" >
                                    </div>
                                </div>
                             </div>


                             <div class="form-group">                       
                               <div class="col-sm-4">
                                    <label for="fund-amount"><span style="color:red">*</span>Amount</label>
                                    <input  type="number"  step="0.01" class="form-control" required="required" id="fund-amount" placeholder="" name="fund-amount" value="" >
                                </div>  
                                
                             </div>


                            <div class="form-group">                       
                               <div class="col-sm-4">
                                    <label for="fund-comment"><span style="color:red">*</span>Comment</label>
                                    <textarea  rows="3" style="display:block; width:100%;" name="fund-comment" required="required" maxlength="250"></textarea>
                                </div>  
                                
                             </div>

                            
                              
                           
                          
                             

                                                 
                            
                             
                              <hr />
                           <button type="submit" class="btn btn-primary" value="1" name="payout" >Save payout</button> 
                        </form>
      
      
      
      				            
            </div>
            <!-- /.box-body -->
          </div>

    </div> <!--/col-sm-8-->
</div>


<div class="row">
    <div class="col-sm-12" >
		<div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Payout History</h3>
             
            </div>
            <!-- /.box-header -->
            <div class="box-body">
             
             
                      <form  enctype="multipart/form-data" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER['SCRIPT_NAME']); ?>" method="post" >
                            
                    </form>


                    <br />
                    <div> <!--pages-->
                   
                        <?php
                            if(!empty($pages)){
                                echo " Pages: ";
                                for($i = 1;$i < $pages + 1; $i++){
                                    if($i == $page_number){
                                        echo "<a class='disabled btn btn-default' href=''>".$i."</a>";
                                    }else{
                                        echo "<a class='btn' href='walletfund.php?page=".$i."'>".$i."</a>";
                                        }  
                                    
                                }
                            }
                        ?>
                    </div><!--/pages-->
                    <br />
                    <table class='table table-bordered'>
                    <thead>
                        <tr>
                        <th>#</th>
                            <th style="">Type</th>    
                            <th style="">Details</th>
                            <th style="">Amount paid out</th>
                            <th style="">Date</th>
                            <th style="">Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                        $count = 1 + (($page_number - 1) * ITEMS_PER_PAGE);
                            foreach($payouts_data as $walletfundingdata){
                                $type = '';
                                $details ="";
                                if($walletfundingdata['fund_type'] == 1){
                                    $type = "Driver";
                                    $details = $walletfundingdata['driver_firstname'] . " " . $walletfundingdata['driver_lastname'] . "(" . $walletfundingdata['driver_phone'] .") wallet was funded by " . $walletfundingdata['staff_firstname'] . " " .  $walletfundingdata['staff_lastname'] ;
                                }elseif($walletfundingdata['fund_type'] == 2){
                                    $type = "Customer";
                                    $details = $walletfundingdata['customer_firstname'] . " " . $walletfundingdata['customer_lastname'] . "(" . $walletfundingdata['customer_phone'] .") wallet was funded by " . $walletfundingdata['staff_firstname'] . " " .  $walletfundingdata['staff_lastname'] ;
                                }else{
                                    $type = "Staff";
                                    $details = $walletfundingdata['customer_firstname'] . " " . $walletfundingdata['customer_lastname'] . "(" . $walletfundingdata['customer_phone'] .") wallet was funded by " . $walletfundingdata['staff_firstname'] . " " .  $walletfundingdata['staff_lastname'] ;
                                }
                                
                                echo "<tr><td>". $count++ . "</td><td>{$type}</td><td>{$details}</td><td>{$walletfundingdata['fund_amount']}</td><td>{$walletfundingdata['wallet_balance']}</td><td>{$walletfundingdata['date_fund']}</td><td>{$walletfundingdata['fund_comment']}</td></tr>";
                            }
                       
                        ?>
                    </tbody>
                    </table>
                                  
                   <?php if(!$number_of_payouts){ echo "<h1 style='text-align:center;'>Nothing to Show. No payout history record</h1>";} ?>
      
      
      
      				            
            </div>
            <!-- /.box-body -->
          </div>

    </div> <!--/col-sm-8-->
</div>

<script>

    jQuery(function () {

        var opt_val =  jQuery("#scope").find(':selected').val();

        if(opt_val == 1){
            jQuery("#type-driver").show();
            jQuery("#type-franchise").hide();
            jQuery("#type-partner").hide();
            jQuery("#type-others").hide();
        }else if(opt_val == 2){
            jQuery("#type-driver").hide();
            jQuery("#type-franchise").show();
            jQuery("#type-partner").hide();
            jQuery("#type-others").hide();
        }else if(opt_val == 3){
            jQuery("#type-driver").hide();
            jQuery("#type-franchise").hide();
            jQuery("#type-partner").show();
            jQuery("#type-others").hide();
        }else if(opt_val == 4){
            jQuery("#type-driver").hide();
            jQuery("#type-franchise").hide();
            jQuery("#type-partner").hide();
            jQuery("#type-others").show();
        }

        jQuery('#scope').on('change', function(){

        var opt_val =  jQuery("#scope").find(':selected').val();

             if(opt_val == 1){
                jQuery("#type-driver").show();
                jQuery("#type-franchise").hide();
                jQuery("#type-partner").hide();
                jQuery("#type-others").hide();
            }else if(opt_val == 2){
                jQuery("#type-driver").hide();
                jQuery("#type-franchise").show();
                jQuery("#type-partner").hide();
                jQuery("#type-others").hide();
            }else if(opt_val == 3){
                jQuery("#type-driver").hide();
                jQuery("#type-franchise").hide();
                jQuery("#type-partner").show();
                jQuery("#type-others").hide();
            }else if(opt_val == 4){
                jQuery("#type-driver").hide();
                jQuery("#type-franchise").hide();
                jQuery("#type-partner").hide();
                jQuery("#type-others").show();
            }


        });

    });









</script>
















