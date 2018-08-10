import ListingsService from "../../../../services/ListingsService";
import {NOTIFICATION_MODULE,noticationMutations,notificationTypes,notificationActions} from "../../../../constants/notificationConstants";

let id = 1;

export const UploadListingFile = {
    data: () => ({
        photos: [],
        maxPhotosCount: 3,
        photosLinks: [],
        isUpdatePhotoLoading: false
    }),
    methods: {
        uploadPhoto(e)
        {
            let file = e.target.files[0];

            let reader = new FileReader();

            reader.onload = (e) => {
                  let imgContent = e.target.result;
                  file.src = imgContent;
                  file.id = id++;
                  this.photos.push(file);
            }

            reader.readAsDataURL(file);
        },
        removePhotoFromComponent(id)
        {
            this.photos = this.photos.filter(file => file.id !== id);
        },
        removePhotoFromServer(photoInfo)
        {
            this.isUpdatePhotoLoading = true;

           ListingsService.removePhoto({
                id: photoInfo.id,
                listing_id: photoInfo.listing_id
            }).then(data => {
                this.isUpdatePhotoLoading = false;
                this.photosLinks = this.photosLinks.filter(photo => photo.id != photoInfo.id);
                this.$store.dispatch(`${NOTIFICATION_MODULE}/${notificationActions.DISPLAY_NOTIFICATION}`, {
                    msg: data.msg
                })
               console.log("REMOVED");
            }).catch(err => {
                this.isUpdatePhotoLoading = false;
           })
        },
        uploadPhotoToServer(e)
        {
            let file = e.target.files[0];
            let formData = new FormData();
            formData.append('photo', file);
            formData.append('id', this.listing.id )
            this.isUpdatePhotoLoading = true;

            ListingsService.uploadPhoto(formData).then(data => {
                this.isUpdatePhotoLoading = false;
                this.photosLinks.push(data.listingImage);
                this.$store.dispatch(`${NOTIFICATION_MODULE}/${notificationActions.DISPLAY_NOTIFICATION}`, {
                    msg: data.msg
                })
                console.log("UPDLOADED");
            }).catch(err => {
                this.isUpdatePhotoLoading = false;
            })
        }
    },
    computed:
    {
        needShowPhotosContainer(){

               return this.photos.length < this.maxPhotosCount;
        },
        needShowPhotosUpdateContainer()
        {
            return this.photosLinks.length < this.maxPhotosCount;
        }
    }
};