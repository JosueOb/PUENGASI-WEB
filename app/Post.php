<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Reaction;

class Post extends Model
{
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'posts';
    public $timestamps = true;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['title', 'description'];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'ubication' => 'array',
        'additional_data' => 'array',
    ];

    /*TODO: SCOPES MODELO */    

    /**
     *Filtra un POST por el Id
     *
	 * @param  mixed $query
	 * @param  integer $id
	 * @return mixed
	 */
    public function scopeFindById($query, $id)
    {
        return $query->where('id', $id);
    }

    /**
	 *Filtra un POST por el Id de la categoria asociada
	 *
	 * @param  mixed $query
	 * @param  int $categoryId
	 * @return mixed
	 */
    public function scopeCategoryId($query, int $categoryId) {
        return $query->where('category_id', $categoryId);
    }

    /**
	 *Filtra un POST por el Id de la subcategoria asociada
	 *
	 * @param  mixed $query
	 * @param  int $categoryId
	 * @return mixed
	 */
    public function scopeSubCategoryId($query, int $subcategoryId) {
        return $query->where('subcategory_id', $subcategoryId);
    }

    /**
	 *Filtra un POST por el Id del usuario asociado
	 *
	 * @param  mixed $query
	 * @param  integer $userId
	 * @return mixed
	 */
    public function scopeUserId($query, string $userId) {
        return $query->where('user_id', $userId);
    }


    /*TODO: RELACIONES DE LOS MODELOS */
    
    /**
     * A post belongs to a user
     */
    public function user(){
        return $this->belongsTo(User::class);
    }
    
    /**
     * A post belongs to a category
     */
    public function category(){
        return $this->belongsTo(Category::class);
    }
    /**
     * A post belongs to a subcategory
     */
    public function subcategory(){
        return $this->belongsTo(Subcategory::class);
    }
    /**
     * A post can have many resources
     */
    public function resources(){
        return $this->hasMany(Resource::class);
    }

    /**
	 *Relacion Uno a Muchos con la tabla Reactions
	 *
	 * @return mixed
	 */
    public function reactions()
    {
        return $this->hasMany(Reaction::class, 'post_id')->orderBy('id','DESC');

    }

    /*TODO: FUNCIONES EXTRAS MODELO */

    /**
     * Get the first image belonging to a post
     */
    public function getFirstImage(){
        $image = $this->resources()->where('type','image')->first();
        
        if($image){
            $image_url = $image->getLink();
        }else{
            //en caso de no tener imagen se retorna una por defecto
            $image_url = 'https://siu-dev97-sd.s3-sa-east-1.amazonaws.com/recursos_publicos/category_icons/report_icon_default.jpg';
        }
        return $image_url;
    }

    /**
    * Get all of the public service's phones.
    */
    public function phones()
    {
        return $this->morphMany(Phone::class, 'phoneable');
    }

}
