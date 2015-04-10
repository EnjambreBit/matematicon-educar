from os import listdir
from os.path import isfile, join
import shutil

mypath = '.'
onlyfiles = [ f for f in listdir(mypath) if isfile(join(mypath,f)) ]

orden = "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
zonas = []
for f in onlyfiles:
    if f.endswith('.png'):
        tmp = f.split('.')[0]
        tmp = tmp.split('_')[2]
        a =str(orden.find(tmp[0]))
        b =str(int(tmp[1]) - 1)  
        new =  a + "-" + b + ".png"
        shutil.copy(f, new)
        zonas.append(int(b) * 20 + int(a))
        print '{"id": "scene_1_'+a+'-'+b+'", "src": "assets/backgrounds/scene_1/'+new+'"},'
print zonas
