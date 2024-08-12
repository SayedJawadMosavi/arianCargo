<?php
 
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Models\GeneralHeadMessage;
use App\Models\Advertisement;
use App\Models\Gallery;
use App\Models\Job;
use App\Models\News;
use App\Models\WaterMessage;
use App\Models\Comments;


    /**
 * Created by PhpStorm.
 * User: habib rahnam
 * Date: 05/23/2017
 * Time: 12:12 PM
 */
   
  
function changeDateFormat($date='',$type=0,$report='')
{
    $is_persian=false;

    if($date && $date !='')
    {
       $dateArray=explode('/', $date);
       if(count($dateArray)<3)
       {
         return Carbon::now();
       }
       if($dateArray[0] !=convert($dateArray[0]))
           $is_persian=true;
       $year=convert($dateArray[0]);
       $month=convert($dateArray[1]);
       $day=convert($dateArray[2]);
       $temp='';    
       if($is_persian)
       {
        $temp= \Morilog\Jalali\jDateTime::createCarbonFromFormat(
            'Y/m/d',$year.'/'.$month.'/'.$day);
        $mydate=Carbon::now();
        $mydate->year=$temp->year;
        $mydate->month=$temp->month;
        $mydate->day=$temp->day;
        $temp=$mydate;
        }
        else
        {
            //$temp=Carbon::create($year,$month,$day,1);
            $mydate=Carbon::now();
            $mydate->year=$year;
            $mydate->month=$month;
            $mydate->day=$day;
            $temp=$mydate;
        }
        if($type)
        {   
            
        }
       if($report !='' && $report)
        {
            return $temp->format('Y-m-d');
        }
        else
        {
            return $temp;
        }
    
    //return strtotime($temp->addDay(1));
}
else
{
    return $date;
}
} 

        function convert($string) {
            $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
            $arabic = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١','٠'];

            $num = range(0, 9);
            $convertedPersianNums = str_replace($persian, $num, $string);
            $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);

            return $englishNumbersOnly;
        }
// to convert date base on user setting or application lang
function convertDate($date='',$time=0)
{
    $date_type='';
   
    $is_en=App::isLocale('en');
    $temp='';
    if($date && $date !='')
    {
        if($date_type=='jalali' || !$is_en)   
        {

            if($time && $time !=0)
            {
                $temp=\Morilog\Jalali\jDate::forge($date)->format('datetime');
            }
            else
            {
                $temp=\Morilog\Jalali\jDate::forge($date)->format('date');
            }
           
        }
        else
        {
            $temp=$date; 
            
            if($time && $time !=0)
            {
               $v=date('Y-m-d',strtotime($temp));
               $temp=$v;
            }
            else
            {
                
            }
        }

}

return $temp;
}

function subsrtStrings($string,$lenght)
{
    return substr($string,$lenght);
} 
 
    function showWaterMessage()
    {
        $waterMessage      = WaterMessage::first();
        return $waterMessage;
    }

    function showPresidentMessage()
    {
        $president         = new GeneralHeadMessage();
        $presidentMessage  = $president->getMessage(language()); 
        
        return $presidentMessage;
    }
    function job()
    {
        $job              = new Job();
        $jobs           = $job->sidebarJob(language()); 
        return $jobs;
    }
    function advertisement()
    {
        $advertisement      = new Advertisement();
        $advertisements      = $advertisement->sidebarAdvertisement(language());
 
        if($advertisements)
        {
            $advertisements      = $advertisements->map(function($advertisements){
                    return [
                        'title'  => $advertisements->title,
                        'id'     => $advertisements->id
                    ];
            });
        }
        return $advertisements;
    }
    function showBayagani()
    {
        $news              = new News();
        $archive           = $news->getArchive(language());
 
        if($archive)
        {
            $archive      = $archive->map(function($archive){
                    return [
                        'title'        => $archive->title,
                        'id'           => $archive->id,
                        'photo'        => $archive->photo,
                        'description'  => $archive->description,
                        'type'         => $archive->type
                    ];
            });
        }
        return $archive;
    }
    function getRelatedPosts($existId,$table, $type ='')
    {
        $lang            = language();
        $relatedPost ='';
        if($type !=null && $type!="monthly" && $type!="library" && $type!="article" && $type!="job" && $type!="Announcement" && $type!="Applicant")
        {
            $relatedPost    = \DB::table($table)->selectRaw("title_$lang as title,id,photo")
            ->whereNotIn('id',[$existId])
            ->where('category',$type)
            ->orderBy('id','desc')
            ->take(3)
            ->get();
        }
        if($type !=null && $type=="monthly")
        {
            $relatedPost    = \DB::table($table)->selectRaw("title_$lang as title,id,photo")
            ->whereNotIn('id',[$existId])
            ->orderBy('id','desc')
            ->take(3)
            ->get();
        }
        if($type !=null && $type=="article" || $type=="library")
        {
            $relatedPost    = \DB::table($table)->selectRaw("title_$lang as title,id,photo")
            ->whereNotIn('id',[$existId])
            ->where('type',$type)
            ->orderBy('id','desc')
            ->take(3)
            ->get();
        }
        if($type !=null && $type=="Applicant" || $type=="Announcement")
        {
            $relatedPost    = \DB::table($table)->selectRaw("title_$lang as title,id,file photo")
            ->whereNotIn('id',[$existId])
            // ->where('type',$type)
            ->orderBy('id','desc')
            ->take(3)
            ->get();
        }
        if($type !=null && $type=="job")
        {
            $relatedPost    = \DB::table($table)->selectRaw("title_$lang as title,id,null as photo")
            ->whereNotIn('id',[$existId])
            ->orderBy('id','desc')
            ->take(3)
            ->get();
        }
        if($type=='')
        {
            $relatedPost    = \DB::table($table)->selectRaw("title_$lang as title,id,file as photo")
            ->whereNotIn('id',[$existId])
            ->orderBy('id','desc')
            ->take(3)
            ->get();
        }
        if($relatedPost)
        {
            $relatedPost      = $relatedPost->map(function($relatedPost){
                    return [
                        'title'  => $relatedPost->title,
                        'id'     => $relatedPost->id,
                        'photo'  => $relatedPost->photo
                    ];
            });
        }
        return $relatedPost;
    }

    function getComments($post_id)
    {
        $comment              = new Comments();
        $comments            = $comment->getComment($post_id);
        if($comments)
        {
            $comments      = $comments->map(function($comments){
                    return [
                        'email'       => $comments->email,
                        'comment'     => $comments->comment,
                        'date'        => $comments->date,
                        'name'        => $comments->name,
                    ];
            });
        }
        return $comments;
    }
    function countComment($post_id)
    {
        $comment              = new Comments();
        $comments             = $comment->getTotalComments($post_id);
        return $comments;
    }
    function language()
    {
        $locale            = \App::getLocale();
        return $locale;
    }
    function checkExtension($file)
    {
        $extensions  = '';
        $extension = \File::extension($file);
        if($file!=Null)
        {
           if($extension=='png' || $extension=='jpg' || $extension=='gif' || $extension=='jpeg' || $extension=='TIFF ' || $extension=='PSD ' )
           {
               $extensions  ='image';
           }
           else {
               $extensions  ='otherFile';
           }
        }
        return $extensions;
    }

    function totalPhoto($gallery_id)
    {
        $gallery   = new Gallery();
        $galleryC  = $gallery->countPhoto($gallery_id);
        return $galleryC;
    }
    function getArchive()
    {
        $archive   = new News();
        $archives  = $archive->getArchives(language());
        return $archives;
    }
    function gallery()
    {
        $gallery     = new Gallery();
        $gallerys      = $gallery->galleryList(language(),'photo');
 
        // if($gallerys)
        // {
        //     $gallerys      = $gallerys->map(function($gallerys){
        //             return [
        //                 'photo'  => $gallerys->file,
        //                 'id'     => $gallerys->id
        //             ];
        //     });
        // }
        return $gallerys;
    }
    
 

