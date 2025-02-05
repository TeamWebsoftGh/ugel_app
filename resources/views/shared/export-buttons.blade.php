<?php
/**
 * Created by PhpStorm.
 * User: Socrates
 * Date: 9/30/2019
 * Time: 4:34 PM
 */
?>
<div class="dropdown dropdown-inline">
    <div class="row">
        <div class="btn-group">
            <div class="btn-group">
                <button data-bs-toggle="dropdown" class="btn btn-primary @if(isset($class)){!! $class !!} @endif dropdown-toggle" type="button"> Export To <span class="caret"></span> </button>
                <ul class="dropdown-menu">
                    <a href="#" class="excel-export-btn"><li style="padding:4px"><i class="text-success fa fa-file-excel-o"></i> &nbsp;Excel</li></a>
                    <a href="#" class="download-btn"><li style="padding:4px"><i class="text-danger fa fa-file-pdf-o"></i> &nbsp;PDF</li></a>
                    <a href="#" class="html-export-btn"><li style="padding:4px"><i class="text-danger fa fa-html5"></i> &nbsp;HTML</li></a>
                </ul>
            </div>
        </div>
    </div>
</div>

