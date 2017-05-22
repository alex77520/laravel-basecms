@extends('web.v1.base.admin')
@section('title', '用户首页')
@section('container')
    @parent
    <section class="app-content">
		<div class="row">
			<div class="col-md-6 col-sm-6">
				<div class="widget p-md clearfix">
					<div class="pull-left">
                        <h3 class="widget-title">会员列表</h3>
						<small class="text-color">Wechat + Mobile</small>
					</div>
					<span class="pull-right fz-lg fw-500 counter" data-plugin="counterUp">102</span>
				</div><!-- .widget -->
			</div>

			<div class="col-md-6 col-sm-6">
				<div class="widget p-md clearfix">
					<div class="pull-left">
						<h3 class="widget-title">Active</h3>
						<small class="text-color">Loads / contact</small>
					</div>
					<span class="pull-right fz-lg fw-500 counter" data-plugin="counterUp">325</span>
				</div><!-- .widget -->
			</div>
		</div><!-- .row -->

		<div class="row">
			<div class="col-md-3 col-sm-6">
				<div class="widget stats-widget">
					<div class="widget-body clearfix">
						<div class="pull-left">
							<h3 class="widget-title text-primary"><span class="counter" data-plugin="counterUp">66.136</span>k</h3>
							<small class="text-color">Total loads</small>
						</div>
						<span class="pull-right big-icon watermark"><i class="fa fa-paperclip"></i></span>
					</div>
					<footer class="widget-footer bg-primary">
						<small>% charge</small>
						<span class="small-chart pull-right" data-plugin="sparkline" data-options="[4,3,5,2,1], { type: 'bar', barColor: '#ffffff', barWidth: 5, barSpacing: 2 }"><canvas width="33" height="16" style="display: inline-block; width: 33px; height: 16px; vertical-align: top;"></canvas></span>
					</footer>
				</div><!-- .widget -->
			</div>

			<div class="col-md-3 col-sm-6">
				<div class="widget stats-widget">
					<div class="widget-body clearfix">
						<div class="pull-left">
							<h3 class="widget-title text-danger"><span class="counter" data-plugin="counterUp">3.490</span>k</h3>
							<small class="text-color">Total Pending</small>
						</div>
						<span class="pull-right big-icon watermark"><i class="fa fa-ban"></i></span>
					</div>
					<footer class="widget-footer bg-danger">
						<small>% charge</small>
						<span class="small-chart pull-right" data-plugin="sparkline" data-options="[1,2,3,5,4], { type: 'bar', barColor: '#ffffff', barWidth: 5, barSpacing: 2 }"><canvas width="33" height="16" style="display: inline-block; width: 33px; height: 16px; vertical-align: top;"></canvas></span>
					</footer>
				</div><!-- .widget -->
			</div>

			<div class="col-md-3 col-sm-6">
				<div class="widget stats-widget">
					<div class="widget-body clearfix">
						<div class="pull-left">
							<h3 class="widget-title text-success"><span class="counter" data-plugin="counterUp">8.378</span>k</h3>
							<small class="text-color">Case Close</small>
						</div>
						<span class="pull-right big-icon watermark"><i class="fa fa-unlock-alt"></i></span>
					</div>
					<footer class="widget-footer bg-success">
						<small>% charge</small>
						<span class="small-chart pull-right" data-plugin="sparkline" data-options="[2,4,3,4,3], { type: 'bar', barColor: '#ffffff', barWidth: 5, barSpacing: 2 }"><canvas width="33" height="16" style="display: inline-block; width: 33px; height: 16px; vertical-align: top;"></canvas></span>
					</footer>
				</div><!-- .widget -->
			</div>

			<div class="col-md-3 col-sm-6">
				<div class="widget stats-widget">
					<div class="widget-body clearfix">
						<div class="pull-left">
							<h3 class="widget-title text-warning"><span class="counter" data-plugin="counterUp">3.490</span>k</h3>
							<small class="text-color">Total Pending</small>
						</div>
						<span class="pull-right big-icon watermark"><i class="fa fa-file-text-o"></i></span>
					</div>
					<footer class="widget-footer bg-warning">
						<small>% charge</small>
						<span class="small-chart pull-right" data-plugin="sparkline" data-options="[5,4,3,5,2],{ type: 'bar', barColor: '#ffffff', barWidth: 5, barSpacing: 2 }"><canvas width="33" height="16" style="display: inline-block; width: 33px; height: 16px; vertical-align: top;"></canvas></span>
					</footer>
				</div><!-- .widget -->
			</div>
		</div><!-- .row -->


		<div class="row">
			<div class="col-md-7">
				<div class="widget">
					<header class="widget-header">
						<h4 class="widget-title">Active Leads</h4>
					</header>
					<hr class="widget-separator">
					<div class="widget-body">
						<div class="table-responsive">							
							<table class="table no-cellborder">
								<thead>
									<tr><th>Type</th><th>Lead Name</th><th>Views</th><th>Favorites</th><th>Last Visit</th><th>Last Action</th></tr>
								</thead>
								<tbody>
									<tr><td class="text-primary">Buyer</td><td>Denise Ann</td><td>150</td><td>150</td><td>9:23 AM</td><td><span class="table-icon fa fa-envelope"></span> 11/9/2015</td></tr>
									<tr><td class="text-primary">Buyer</td><td>Denise Ann</td><td>150</td><td>202</td><td>9:23 AM</td><td><span class="table-icon fa fa-envelope"></span> 11/9/2015</td></tr>
									<tr><td class="text-success">Landlord</td><td>Denise Ann</td><td>150</td><td>313</td><td>9:23 AM</td><td><span class="table-icon fa fa-envelope"></span> 11/9/2015</td></tr>
									<tr><td class="text-primary">Buyer</td><td>Denise Ann</td><td>150</td><td>175</td><td>9:23 AM</td><td><span class="table-icon fa fa-envelope"></span> 11/9/2015</td></tr>
									<tr><td class="text-danger">Seller</td><td>Denise Ann</td><td>150</td><td>148</td><td>9:23 AM</td><td><span class="table-icon fa fa-envelope"></span> 11/9/2015</td></tr>
									<tr><td class="text-primary">Buyer</td><td>Denise Ann</td><td>150</td><td>1500</td><td>9:23 AM</td><td><span class="table-icon fa fa-envelope"></span> 11/9/2015</td></tr>
									<tr><td class="text-primary">Buyer</td><td>Denise Ann</td><td>150</td><td>1270</td><td>9:23 AM</td><td><span class="table-icon fa fa-envelope"></span> 11/9/2015</td></tr>
									<tr><td class="text-danger">Buyer</td><td>Denise Ann</td><td>150</td><td>3201</td><td>9:23 AM</td><td><span class="table-icon fa fa-envelope"></span> 11/9/2015</td></tr>
								</tbody>
							</table>
						</div>
					</div>
				</div><!-- .widget -->
			</div><!-- END column -->

			<div class="col-md-5">
				<div class="widget todo-widget">
					<header class="widget-header">
						<h4 class="widget-title">Todo List</h4>
					</header>
					<hr class="widget-separator">
					<div class="widget-body p-b-0">
						<ul class="todo-list">
							<li class="todo-item">
								<div class="checkbox checkbox-default">
									<input type="checkbox" id="checkbox02">
									<label for="checkbox02">Record The First Episode Of HTML Tutorial</label>
								</div>
							</li><!-- .todo-item -->

							<li class="todo-item">
								<div class="checkbox checkbox-pink">
									<input type="checkbox" id="checkbox2">
									<label for="checkbox2">Prepare The Conference Schedule</label>
								</div>
							</li><!-- .todo-item -->

							<li class="todo-item">
								<div class="checkbox checkbox-warning">
									<input type="checkbox" id="checkbox4" checked="">
									<label for="checkbox4">Decide The Live Discussion Time</label>
								</div>
							</li><!-- .todo-item -->

							<li class="todo-item">
								<div class="checkbox checkbox-success">
									<input type="checkbox" id="checkbox3" checked="">
									<label for="checkbox3">Prepare For The Next Project</label>
								</div>
							</li><!-- .todo-item -->

							<li class="todo-item">
								<div class="checkbox checkbox-danger">
									<input type="checkbox" id="checkbox5" checked="">
									<label for="checkbox5">Finish Up AngularJs Tutorial</label>
								</div>
							</li><!-- .todo-item -->

							<li class="todo-item">
								<div class="checkbox checkbox-purple">
									<input type="checkbox" id="checkbox1" checked="">
									<label for="checkbox1">Finish Infinity Project</label>
								</div>
							</li><!-- .todo-item -->
						</ul><!-- .todo-list -->
					</div>
					<div class="new-todo">
						<input type="text" placeholder="add new task">
					</div>
					<footer class="widget-footer">
						<a href="javascript:void(0)" class="btn btn-sm btn-success m-r-md">All</a>
						<a href="javascript:void(0)" class="btn btn-sm btn-default">Archive</a>
						<a href="javascript:void(0)" class="btn btn-sm btn-default pull-right">Clear completed</a>
					</footer>
				</div><!-- .widget -->
			</div>
		</div><!-- .row -->


		<div class="row">
			<div class="col-md-6 col-sm-6">
				<div class="widget">
					<header class="widget-header">
						<h4 class="widget-title">Feeds</h4>
					</header>
					<hr class="widget-separator">
					<div class="widget-body">
						<div class="media-group feeds-group">

							<div class="media-group-item">
								<div class="media">
									<div class="media-left">
										<div class="avatar avatar-sm avatar-circle">
											<img src="../assets/images/217.jpg" alt="">
										</div>
									</div>
									<div class="media-body">
										<h5><a href="javascript:void(0)" class="text-color">Some of the fantastic things people have had to say about Ooooh</a></h5>
										<small class="text-muted">2 days ago</small>
									</div>
								</div>
							</div><!-- .media-group-item -->

							<div class="media-group-item">
								<div class="media">
									<div class="media-left">
										<div class="avatar avatar-sm avatar-circle">
											<img src="../assets/images/218.jpg" alt="">
										</div>
									</div>
									<div class="media-body">
										<h5><a href="javascript:void(0)" class="text-color">Here are just some of the magazine reviews we have had</a></h5>
										<small class="text-muted">1 day ago</small>
									</div>
								</div>
							</div><!-- .media-group-item -->

							<div class="media-group-item">
								<div class="media">
									<div class="media-left">
										<div class="avatar avatar-sm avatar-circle">
											<img src="../assets/images/219.jpg" alt="">
										</div>
									</div>
									<div class="media-body">
										<h5><a href="javascript:void(0)" class="text-color">Lorem ipsum dolor amet, consectetur adipisicing elit.</a></h5>
										<small class="text-muted">2 days ago</small>
									</div>
								</div>
							</div><!-- .media-group-item -->

							<div class="media-group-item">
								<div class="media">
									<div class="media-left">
										<div class="avatar avatar-sm avatar-circle">
											<img src="../assets/images/215.jpg" alt="">
										</div>
									</div>
									<div class="media-body">
										<h5><a href="javascript:void(0)" class="text-color">“It’s just brilliant. I will recommend it to everyone!”</a></h5>
										<small class="text-muted">2 mins ago</small>
									</div>
								</div>
							</div><!-- .media-group-item -->

							<div class="media-group-item">
								<div class="media">
									<div class="media-left">
										<div class="avatar avatar-sm avatar-circle">
											<img src="../assets/images/221.jpg" alt="">
										</div>
									</div>
									<div class="media-body">
										<h5><a href="javascript:void(0)" class="text-color">John has just started working on the project</a></h5>
										<small class="text-muted">right now</small>
									</div>
								</div>
							</div><!-- .media-group-item -->
						</div>
					</div>
				</div><!-- .widget -->
			</div>

			<div class="col-md-6 col-sm-6">
				<div class="widget">
					<header class="widget-header">
						<h4 class="widget-title">Streams</h4>
					</header>
					<hr class="widget-separator">
					<div class="widget-body">
						<div class="streamline m-l-lg">
							<div class="sl-item p-b-md">
								<div class="sl-avatar avatar avatar-sm avatar-circle">
									<img class="img-responsive" src="../assets/images/221.jpg" alt="avatar">
								</div><!-- .avatar -->
								<div class="sl-content m-l-xl">
									<h5 class="m-t-0"><a href="javascript:void(0)" class="m-r-xs theme-color">John Doe</a><small class="text-muted fz-sm">last month</small></h5>
									<p>John has just started working on the project</p>
								</div>
							</div><!-- .sl-item -->

							<div class="sl-item p-b-md">
								<div class="sl-avatar avatar avatar-sm avatar-circle">
									<img class="img-responsive" src="../assets/images/214.jpg" alt="avatar">
								</div><!-- .avatar -->
								<div class="sl-content m-l-xl">
									<h5 class="m-t-0"><a href="javascript:void(0)" class="m-r-xs theme-color">Jane Doe</a><small class="text-muted fz-sm">last month</small></h5>
									<p>Jane sent you invitation to attend the party</p>
								</div>
							</div><!-- .sl-item -->

							<div class="sl-item p-b-md">
								<div class="sl-avatar avatar avatar-sm avatar-circle">
									<img class="img-responsive" src="../assets/images/217.jpg" alt="avatar">
								</div><!-- .avatar -->
								<div class="sl-content m-l-xl">
									<h5 class="m-t-0"><a href="javascript:void(0)" class="m-r-xs theme-color">Sally Mala</a><small class="text-muted fz-sm">last month</small></h5>
									<p>Sally added you to her circles</p>
								</div>
							</div><!-- .sl-item -->

							<div class="sl-item p-b-md">
								<div class="sl-avatar avatar avatar-sm avatar-circle">
									<img class="img-responsive" src="../assets/images/211.jpg" alt="avatar">
								</div><!-- .avatar -->
								<div class="sl-content m-l-xl">
									<h5 class="m-t-0"><a href="javascript:void(0)" class="m-r-xs theme-color">Sara Adams</a><small class="text-muted fz-sm">last month</small></h5>
									<p>Sara has finished her task</p>
								</div>
							</div><!-- .sl-item -->
							<div class="sl-item p-b-md">
								<div class="sl-avatar avatar avatar-sm avatar-circle">
									<img class="img-responsive" src="../assets/images/214.jpg" alt="avatar">
								</div><!-- .avatar -->
								<div class="sl-content m-l-xl">
									<h5 class="m-t-0"><a href="javascript:void(0)" class="m-r-xs theme-color">Sandy Doe</a><small class="text-muted fz-sm">last month</small></h5>
									<p>Sara has finished her task</p>
								</div>
							</div><!-- .sl-item -->
						</div><!-- .streamline -->
					</div>
				</div><!-- .widget -->
			</div>
		</div><!-- .row -->

	</div></section>
@stop