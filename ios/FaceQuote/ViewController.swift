//
//  ViewController.swift
//  FaceQuote
//
//  Created by Дмитрий Будко on 14.10.2017.
//  Copyright © 2017 Дмитрий Будко. All rights reserved.
//

import UIKit
import Alamofire

class ViewController: UIViewController, UIImagePickerControllerDelegate, UINavigationControllerDelegate{
    @IBOutlet weak var sharePhoto: UIButton!
    @IBOutlet weak var imageView: UIImageView!
    var activityViewController: UIActivityViewController? = nil
    var activityIndicator: UIActivityIndicatorView = UIActivityIndicatorView()
    
    
    override func viewDidLoad() {
        activityIndicator.center = self.view.center
        activityIndicator.hidesWhenStopped = true
        activityIndicator.activityIndicatorViewStyle = UIActivityIndicatorViewStyle.whiteLarge
        view.addSubview(activityIndicator)
        
        sharePhoto.addTarget(self, action: #selector(buttonClickShow), for: UIControlEvents.touchUpInside)
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
    }
    
    override func viewDidAppear(_ animated: Bool) {
        if imageView.image == nil{
            choseImagePickerAction(sourse: .camera)
        }
    }
    
    @objc func buttonClickShow(paramSender: UIButton) {
        let imageToShare = imageView.image!
        let activitiController = UIActivityViewController(activityItems: [imageToShare], applicationActivities: nil)
        self.present(activitiController, animated: true, completion: nil)
    }
    // upload image to server
    func sendToServer(image:UIImage) {
        let imgData = UIImageJPEGRepresentation(image, 0.2)! as Data
        let url = try! URLRequest(url: URL(string:"http://109.120.138.167:8080/image/upload")!, method: .post, headers: nil)
        Alamofire.upload(
            multipartFormData: { multipartFormData in
                multipartFormData.append(imgData, withName: "imageFile", fileName: "image.jpg", mimeType: "image/jpeg")
        },
            with: url,
            encodingCompletion: { encodingResult in
                switch encodingResult {
                case .success(let upload, _, _):
                    upload.responseJSON { response in
                        if((response.result.value) != nil) {
                            print("Answer from server \(response)")
                            let answer = response.result.value as? NSDictionary
                            if let quote = answer?.value(forKey: "quote") as? String {
                                print(quote)
                                self.imageView.image = self.createFinalImageText(imageIn: image, text: quote)!
                            }
                        } else {
                            print("nil response")
                        }
                    }
                case .failure( _):
                    print("Failure to post")
                    break
                }
        })
    }
    
    func imagePickerController(_ picker: UIImagePickerController, didFinishPickingMediaWithInfo info: [String : Any]) {
        let image = info[UIImagePickerControllerOriginalImage] as? UIImage
        imageView.image = image
        activityIndicator.startAnimating()
        sendToServer(image: image!)
        imageView.contentMode = .scaleToFill
        dismiss(animated: true, completion: nil)
    }
    func choseImagePickerAction(sourse:UIImagePickerControllerSourceType){
        if UIImagePickerController.isSourceTypeAvailable(sourse) {
            //self.imageView.image = UIImage(named: "loading")
            let imagePicker = UIImagePickerController()
            imagePicker.delegate = self
            imagePicker.allowsEditing = false
            imagePicker.sourceType = sourse
            self.present(imagePicker, animated: true, completion: nil)
        }
    }
    
    @IBAction func newPhoto(_ sender: UIButton) {
        //        let alertCntroller = UIAlertController(title: "Источник фотографии", message: nil, preferredStyle: .actionSheet)
        //        let cameraAction = UIAlertAction(title: "Камера", style: .default, handler: { (action) in
        self.choseImagePickerAction(sourse: .camera)
        //        })
        //        let photoLibAction = UIAlertAction(title: "Фото", style: .default, handler: { (action) in
        //            self.choseImagePickerAction(sourse: .photoLibrary)
        //
        //        })
        //        let cancel = UIAlertAction(title: "Отмена", style: .default, handler: nil)
        //        alertCntroller.addAction(cameraAction)
        //        alertCntroller.addAction(photoLibAction)
        //        alertCntroller.addAction(cancel)
        //        self.present(alertCntroller, animated: true, completion: nil)
    }
    
    // return image with text
    func createFinalImageText (imageIn:UIImage, text:String) -> UIImage? {
        let image = imageIn
        
        let viewToRender = UIView(frame: CGRect(x: 0, y: 0, width: self.view.frame.size.width, height: self.view.frame.size.width)) // here you can set the actual image width : image.size.with ?? 0 / height : image.size.height ?? 0
        let imgView = UIImageView(frame: viewToRender.frame)
        imgView.image = image
        viewToRender.addSubview(imgView)
        let textImgView = UIImageView(frame: viewToRender.frame)
        textImgView.image = imageFrom(text: text, size: viewToRender.frame.size)
        viewToRender.addSubview(textImgView)
        UIGraphicsBeginImageContextWithOptions(viewToRender.frame.size, false, 0)
        viewToRender.layer.render(in: UIGraphicsGetCurrentContext()!)
        let finalImage = UIGraphicsGetImageFromCurrentImageContext()
        UIGraphicsEndImageContext()
        activityIndicator.stopAnimating()
        
        return finalImage
    }
    // text for image
    func imageFrom(text: String , size:CGSize) -> UIImage {
        
        let renderer = UIGraphicsImageRenderer(size: size)
        let img = renderer.image { ctx in
            let paragraphStyle = NSMutableParagraphStyle()
            paragraphStyle.alignment = .justified
            paragraphStyle.firstLineHeadIndent = 30
            
            
            let attrs = [NSAttributedStringKey.font: UIFont(name: "HelveticaNeue", size: 18)!, NSAttributedStringKey.foregroundColor: UIColor.white,NSAttributedStringKey.backgroundColor: #colorLiteral(red: 0.2858098149, green: 0.5896097422, blue: 0.9010376334, alpha: 1), NSAttributedStringKey.paragraphStyle: paragraphStyle]
            
            text.draw(with: CGRect(x: 0, y: size.height / 1.3, width: size.width, height: size.height), options: .usesLineFragmentOrigin, attributes: attrs, context: nil)
        }
        return img
    }
}
