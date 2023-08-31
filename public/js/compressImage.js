/*!
 The Original Mahentong Kompres gambar Javascript
 */
 function compressImage(file, options) {
  return new Promise(function(resolve, reject) {
    const image = new Image();
    image.onload = function() {
      let width = image.width;
      let height = image.height;
      if (width > options.maxWidth) {
        height *= options.maxWidth / width;
        width = options.maxWidth;
      }
      if (height > options.maxHeight) {
        width *= options.maxHeight / height;
        height = options.maxHeight;
      }
      const canvas = document.createElement('canvas');
      canvas.width = width;
      canvas.height = height;
      const ctx = canvas.getContext('2d');
      ctx.drawImage(image, 0, 0, width, height);
      canvas.toBlob(function(blob) {
        resolve(new File([blob], file.name, {type: blob.type}));
      }, file.type, options.quality);
    };
    image.onerror = reject;
    image.src = URL.createObjectURL(file);
  });
}
  