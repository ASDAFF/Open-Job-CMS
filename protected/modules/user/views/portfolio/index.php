<?php
/* @var $this PortfolioController */
/* @var $dataProvider CActiveDataProvider */

$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.autosize.min.js', CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/underscore-min.js', CClientScript::POS_END);

$cs->registerCssFile(Yii::app()->baseUrl . '/js/prettyphoto/css/prettyPhoto.css');
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/prettyphoto/js/jquery.prettyPhoto.js');


$this->breadcrumbs=array(
	'User Portfolios',
);

$user = UserModule::user();
$dir = HUser::getUploadUrl($user, HUser::UPLOAD_PORTFOLIO);

HView::echoAndSetTitle('Мое портфолио');
?>

<div id="portfolio_upload">
<?php
$this->widget('ext.EAjaxUpload.EAjaxUpload',
    array(
        'id'=>'uploadFile',
        'config'=>array(
            'action'=>Yii::app()->createUrl('/user/portfolio/upload'),
            'allowedExtensions'=>array("jpg","jpeg","gif", "png"),//array("jpg","jpeg","gif","exe","mov" and etc...
            'sizeLimit'=>1*1024*1024,// maximum file size in bytes
            'minSizeLimit'=>1024,// minimum file size in bytes
            'onComplete'=>"js:function(id, fileName, responseJSON){ portfolio.addImg(responseJSON); }",
            'showMessage'=>"js:function(message){ notify.error(message); }"
        )
    )); ?>
</div>

<div id="portfolio_list" class="items"></div>

<?php
$this->widget('ext.charcounter.CharCounter', array(
    'target' => '.portfolio-desc > textarea',
    'count' => 255,
    'config' => array(
        'container' => '<div></div>',
        'format' => 'Осталось символов: %1',
    ),
));
?>

<script type="text/html" id="portfolio_item">
    <div class="portfolio-item well" id="portfolio-item-<%=portfolio.id%>">
        <div class="portfolio-del" data-id="<%=portfolio.id%>" title="Удалить"><i class="icon-remove"></i></div>
        <div class="portfolio-img">
            <a href="<%=portfolio.sImgSrc%>" rel="prettyPhoto[gallery]" title="<%-portfolio.description%>"><img src="<%=portfolio.sImgSrcThumb%>"></a>
        </div>
        <div class="portfolio-desc">
            <textarea id="portfolio-desc-<%=portfolio.id%>"><%=portfolio.description%></textarea>
            <a class="btn btn-primary btn-mini portfolio-desc-save" data-id="<%=portfolio.id%>">Сохранить</a>
        </div>
    </div>
</script>

<script>
    var dir = <?php echo CJavaScript::encode($dir);?>;
    var restore = [];
    var countPortfolio = <?php echo CJavaScript::encode($user->countPortfolio);?>;
    var portfolioMaxPhoto = <?php echo param('portfolioMaxPhoto');?>;
    var showNotify = false;

    $(function(){
        portfolio.checkMax();

        _.templateSettings.variable = "portfolio";

        portfolio.loadList();

        $('#portfolio_list').on('click', 'div.portfolio-del', function(){
            var $this = $(this);

            var id = $this.attr('data-id');

            $.ajax({
                url: '<?php echo Yii::app()->createUrl('/user/portfolio/ajaxDelete?id=');?>' + id,
                dataType: 'json',
                success: function(data){
                    if(data.status == 'ok'){
                        countPortfolio = data.countPortfolio;
                        portfolio.checkMax();
                        restore[id] = $('#portfolio-item-' + id).html();
                        $('#portfolio-item-' + id).html('<div class="portfolio-restore" data-id="'+id+'" title="Востановить"><a class="btn">Востановить</a></div>');
                        reInitJs();
                    } else {
                        notify.error(data.msg);
                    }
                }
            })
        });

        $('#portfolio_list').on('click', 'div.portfolio-restore', function(){
            var $this = $(this);

            var id = $this.attr('data-id');

            $.ajax({
                url: '<?php echo Yii::app()->createUrl('/user/portfolio/ajaxRestore?id=');?>' + id,
                dataType: 'json',
                success: function(data){
                    if(data.status == 'ok'){
                        countPortfolio = data.countPortfolio;
                        portfolio.checkMax();
                        $('#portfolio-item-' + id).html(restore[id]);

                        reInitJs();
                    } else {
                        notify.error(data.msg);
                    }
                }
            })
        });

        $('#portfolio_list').on('click', 'a.portfolio-desc-save', function(){
            var $this = $(this);

            var id = $this.attr('data-id');
            var desc = $('#portfolio-desc-'+id).val();

            if(!desc){
                notify.error('Пожалуйста напшите что нибудь');
                return false;
            }

            $.ajax({
                url: '<?php echo Yii::app()->createUrl('/user/portfolio/ajaxSave?id=');?>' + id,
                dataType: 'json',
                data: { desc: desc },
                type: 'post',
                success: function(data){
                    if(data.status == 'ok'){
                        notify.info(data.msg);
                        portfolio.loadList();
                        return;
                    }
                    notify.error(data.msg);
                    return;
                }
            })
        });
    });

    var portfolio = {
        checkMax: function(){
            if(countPortfolio >= portfolioMaxPhoto){
                if(showNotify == false){
                    showNotify = true;
                    notify.info('Максимальное количество фото ' + portfolioMaxPhoto );
                }
                $('#portfolio_upload').hide();
            }else{
                $('#portfolio_upload').show();
            }
        },

        addImg: function(data){
            var tpl = _.template($('#portfolio_item').html());

            if(typeof data.success != 'undefined' && data.success == true){
                countPortfolio++;
                portfolio.checkMax();

                $('#portfolio_list').append(tpl(data.portfolio));
            } else {
                notify.error(data.error);
            }
        },

        loadList: function(){
            $.ajax({
                url: <?php echo CJavaScript::encode(Yii::app()->createUrl('/user/portfolio/ajaxLoadList'));?>,
                dataType: 'json',
                success: function(data){
                    countPortfolio = data.countPortfolio;
                    portfolio.checkMax();
                    var tpl = _.template($('#portfolio_item').html());

                    $('#portfolio_list').html('');
                    _.each(data.portfolios, function(portfolio){
                        $('#portfolio_list').append(tpl(portfolio));
                    });

                    reInitJs();
                }
            });
        }
    }

    function reInitJs(){
        $("a[rel^=\'prettyPhoto\']").prettyPhoto(
            {
                animation_speed: "fast",
                slideshow: 10000,
                hideflash: true,
                social_tools: "",
                gallery_markup: "",
                slideshow: 3000,
                autoplay_slideshow: false
                /*slideshow: false*/
            }
        );
        $(".portfolio-desc > textarea").charCounter(255, {
            container: "<div></div>",
            format: "Осталось символов: %1"
        });

        $('textarea').autosize();
    }
</script>