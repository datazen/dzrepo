<?php
/*
  $Id: index.php,v 6.5.4 2017/12/17 01:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2017 Loaded Commerce
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  require_once('includes/application_top.php');

  include(DIR_WS_INCLUDES . 'html_top.php');
  include(DIR_WS_INCLUDES . 'header.php');
  include(DIR_WS_INCLUDES . 'column_left.php'); 

  ?>
  <div id="content" class="content">         
    <h1 class="page-header"><i class="fa fa-laptop"></i> Dashboard</h1>
    <div class="row">
      <!-- begin col-3 -->
      <div class="col-md-3 col-sm-6">
        <div class="widget widget-stats bg-green">
          <div class="stats-icon stats-icon-lg"><i class="fa fa-globe fa-fw"></i></div>
          <div class="stats-title">TODAY'S VISITS</div>
          <div class="stats-number">7,842,900</div>
          <div class="stats-progress progress">
            <div class="progress-bar" style="width: 70.1%;"></div>
          </div>
          <div class="stats-desc">Better than last week (70.1%)</div>
        </div>
      </div>
      <!-- end col-3 -->
      <!-- begin col-3 -->
      <div class="col-md-3 col-sm-6">
        <div class="widget widget-stats bg-blue">
          <div class="stats-icon stats-icon-lg"><i class="fa fa-tags fa-fw"></i></div>
          <div class="stats-title">TODAY'S PROFIT</div>
          <div class="stats-number">180,200</div>
          <div class="stats-progress progress">
            <div class="progress-bar" style="width: 40.5%;"></div>
          </div>
          <div class="stats-desc">Better than last week (40.5%)</div>
        </div>
      </div>
      <!-- end col-3 -->
      <!-- begin col-3 -->
      <div class="col-md-3 col-sm-6">
        <div class="widget widget-stats bg-purple">
          <div class="stats-icon stats-icon-lg"><i class="fa fa-shopping-cart fa-fw"></i></div>
          <div class="stats-title">NEW ORDERS</div>
          <div class="stats-number">38,900</div>
          <div class="stats-progress progress">
            <div class="progress-bar" style="width: 76.3%;"></div>
          </div>
          <div class="stats-desc">Better than last week (76.3%)</div>
        </div>
      </div>
      <!-- end col-3 -->
      <!-- begin col-3 -->
      <div class="col-md-3 col-sm-6">
        <div class="widget widget-stats bg-black">
          <div class="stats-icon stats-icon-lg"><i class="fa fa-comments fa-fw"></i></div>
          <div class="stats-title">NEW COMMENTS</div>
          <div class="stats-number">3,988</div>
          <div class="stats-progress progress">
            <div class="progress-bar" style="width: 54.9%;"></div>
          </div>
          <div class="stats-desc">Better than last week (54.9%)</div>
        </div>
      </div>
      <!-- end col-3 -->
    </div>
    <!-- end row -->
    <!-- begin row -->
    <div class="row">
      <div class="col-md-8">
        <div class="widget-chart with-sidebar bg-black">
          <div class="widget-chart-content">
            <h4 class="chart-title">
              Visitors Analytics
              <small>Where do our visitors come from</small>
            </h4>
            <div id="visitors-line-chart" class="morris-inverse" style="height: 260px;"></div>
          </div>
          <div class="widget-chart-sidebar bg-black-darker">
            <div class="chart-number">
              1,225,729
              <small>visitors</small>
            </div>
            <div id="visitors-donut-chart" style="height: 160px"></div>
            <ul class="chart-legend">
              <li><i class="fa fa-circle-o fa-fw text-success m-r-5"></i> 34.0% <span>New Visitors</span></li>
              <li><i class="fa fa-circle-o fa-fw text-primary m-r-5"></i> 56.0% <span>Return Visitors</span></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="panel panel-inverse" data-sortable-id="index-1">
          <div class="panel-heading">
            <h4 class="panel-title">
              Visitors Origin
            </h4>
          </div>
          <div id="visitors-map" class="bg-black" style="height: 181px;"></div>
          <div class="list-group">
            <a href="#" class="list-group-item list-group-item-inverse text-ellipsis">
              <span class="badge badge-success">20.95%</span>
              1. United State 
            </a> 
            <a href="#" class="list-group-item list-group-item-inverse text-ellipsis">
              <span class="badge badge-primary">16.12%</span>
              2. India
            </a>
            <a href="#" class="list-group-item list-group-item-inverse text-ellipsis">
              <span class="badge badge-inverse">14.99%</span>
              3. South Korea
            </a>
          </div>
        </div>
      </div>
    </div>
    <!-- end row -->
    <div class="row">    
      <?php
      echo $cre_RCI->get('index', 'blockleft');
      echo $cre_RCI->get('index', 'blockright');
      ?>
    </div>        
  </div>
  <?php 
  include(DIR_WS_INCLUDES . 'html_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php'); 
  ?>