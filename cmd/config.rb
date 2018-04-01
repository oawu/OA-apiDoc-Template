require 'compass/import-once/activate'

# 網域(domain)後面的目錄
http_path = "/oaf2e"


# 字體目錄與網址下的字體目錄
fonts_dir = "font"
fonts_path = "../font"

# 圖片目錄與網址下的圖片目錄
images_dir = "img"
images_path = "../img"

# css 目錄與 scss 目錄
css_dir = "../css"
sass_dir = "../scss"

# 其他要匯入的資源
add_import_path = "./imports"
additional_import_paths = ["./imports"]

# 選擇輸出的 css 類型，:expanded or :nested or :compact or :compressed
  # nested     有縮排 沒壓縮
  # expanded   沒縮排 沒壓縮
  # compact    有換行 有壓縮(半壓縮)
  # compressed 沒縮排 有壓縮(全壓縮)
output_style = :compressed

# 在 css 中加入註解相對應於 scss 的第幾行，false、true
  # false     不需加入註解
  # true      需要加入註解
line_comments = false

# To enable relative paths to assets via compass helper functions. Uncomment:
# relative_assets = true

# images_dir = "images"
# javascripts_dir = "javascripts"

