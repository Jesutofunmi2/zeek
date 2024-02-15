<div class="box box-success">
        <!-- <div class="box-header with-border">
        <h3 class="box-title">Options</h3>
        
        </div> -->
        <!-- /.box-header -->
        <div class="box-body">
        
        
                <form  enctype="multipart/form-data" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER['SCRIPT_NAME']); ?>" method="post" >

                        <div class="form-group">
                            
                            <div class="col-sm-6">
                                <label for="rider-app-playstore-url"><span style="color:red">*</span>Rider Android App Playstore URL</label>
                                <p>Playstore URL required to redirect user to App page for rating and update.</p>
                                <input  type="text" required class="form-control" id="rider-app-playstore-url" placeholder="" name="rider-app-playstore-url" value="<?php echo isset($settings_data3['rider-app-playstore-url']) ? $settings_data3['rider-app-playstore-url'] : ''; ?>" >
                            </div>  

                            <div class="col-sm-6">
                                <label for="rider-app-appstore-url"><span style="color:red">*</span>Rider IOS App Appstore URL</label>
                                <p>App store URL required to redirect user to App page for rating and update.</p>
                                <input type="text" required class="form-control" id="rider-app-appstore-url" placeholder="" name="rider-app-appstore-url" value="<?php echo isset($settings_data3['rider-app-appstore-url']) ? $settings_data3['rider-app-appstore-url'] : ''; ?>" >
                            </div>
                            
                        </div>
                        <hr>
                        <div class="form-group">
                        
                            <div class="col-sm-6">
                                <label for="driver-app-playstore-url"><span style="color:red">*</span>Driver Android App Playstore URL</label>
                                <p>Playstore URL required to redirect user to App page for rating and update.</p>
                                <input  type="text" required class="form-control" id="driver-app-playstore-url" placeholder="" name="driver-app-playstore-url" value="<?php echo isset($settings_data3['driver-app-playstore-url']) ? $settings_data3['driver-app-playstore-url'] : ''; ?>" >
                            </div>  

                            <div class="col-sm-6">
                                <label for="driver-app-appstore-url"><span style="color:red">*</span>Driver IOS App Appstore URL</label>
                                <p>App store URL required to redirect user to App page for rating and update.</p>
                                <input type="text" required class="form-control" id="driver-app-appstore-url" placeholder="" name="driver-app-appstore-url" value="<?php echo isset($settings_data3['driver-app-appstore-url']) ? $settings_data3['driver-app-appstore-url'] : ''; ?>" >
                            </div>
                            
                        </div>

                        <hr>

                        <div class="form-group">
                        
                            <div class="col-sm-6">
                                <label for="rider-android-app-version"><span style="color:red">*</span>Rider Android App Version</label>
                                <p>Version of the Rider Android App. Must be the same with the App else an Update prompt will be triggered on App</p>
                                <input type="text" required class="form-control" id="rider-android-app-version" placeholder="" name="rider-android-app-version" value="<?php echo isset($settings_data3['rider-android-app-version']) ? $settings_data3['rider-android-app-version'] : ''; ?>" >
                            </div>  

                            <div class="col-sm-6">
                                <label for="rider-ios-app-version"><span style="color:red">*</span>Rider IOS App Version</label>
                                <p>Version of the Rider IOS App. Must be the same with the App else an Update prompt will be triggered on App</p>
                                <input type="text" required class="form-control" id="rider-ios-app-version" placeholder="" name="rider-ios-app-version" value="<?php echo isset($settings_data3['rider-ios-app-version']) ? $settings_data3['rider-ios-app-version'] : ''; ?>" >
                            </div>  
                            
                        </div>
                        <hr>
                        <div class="form-group">
                        
                            <div class="col-sm-6">
                                <label for="driver-android-app-version"><span style="color:red">*</span>Driver Android App Version</label>
                                <p>Version of the Driver Android App. Must be the same with the App else an Update prompt will be triggered on App</p>
                                <input type="text" required class="form-control" id="driver-android-app-version" placeholder="" name="driver-android-app-version" value="<?php echo isset($settings_data3['driver-android-app-version']) ? $settings_data3['driver-android-app-version'] : ''; ?>" >
                            </div>  

                            <div class="col-sm-6">
                                <label for="driver-ios-app-version"><span style="color:red">*</span>Driver IOS App Version</label>
                                <p>Version of the Driver IOS App. Must be the same with the App else an Update prompt will be triggered on App</p>
                                <input type="text" required class="form-control" id="driver-ios-app-version" placeholder="" name="driver-ios-app-version" value="<?php echo isset($settings_data3['driver-ios-app-version']) ? $settings_data3['driver-ios-app-version'] : ''; ?>" >
                            </div>  
                            
                        </div>



                    <button type="submit" class="btn btn-primary btn-block" value="1" name="savesettings3" >Save</button> 
                </form>



                            
        </div>
        <!-- /.box-body -->
    </div>